<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\Demande;
use App\Models\JourFerie;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    private function creerAbsenceAutomatiqueSiNecessaire($employe): void
    {
        if (! $employe) {
            return;
        }

        $presenceDuJour = Presence::where('employe_id', $employe->id)
            ->whereDate('date_presence', today())
            ->first();

        if ($presenceDuJour) {
            return;
        }

        $heureFin = config('pointage.heure_fin', '18:30');
        [$heure, $minute] = explode(':', $heureFin);

        $heureLimiteAbsence = now()->copy()->setTime((int) $heure, (int) $minute, 0);

        if (! now()->greaterThan($heureLimiteAbsence)) {
            return;
        }

        $demandePermission = Demande::with('permission')
            ->where('employe_id', $employe->id)
            ->where('type_demande', 'permission')
            ->where('statut', 'en_attente')
            ->get()
            ->first(function ($demande) {
                return $demande->permission
                    && $demande->permission->date_permission
                    && Carbon::parse($demande->permission->date_permission)->isToday();
            });

       $statut = 'absent';

        if ($this->estWeekend()) {
            $statut = 'weekend';
        } else {
            $jourFerie = $this->recupererJourFerieDuJour();

            if ($jourFerie) {
                $statut = 'ferie';
            } else {
                $conge = $this->recupererCongeActif($employe);

                if ($conge) {
                    $statut = 'conge';
                }
            }
        }


        if ($statut === 'absent' && $demandePermission && $demandePermission->permission) {
            $permission = $demandePermission->permission;

            $heureDebutTravail = config('pointage.heure_debut', '08:30');
            $heureFinTravail = config('pointage.heure_fin', '18:30');

            $permissionJourneeEntiere = $permission->heure_debut <= $heureDebutTravail
                && $permission->heure_fin >= $heureFinTravail;

            if ($permissionJourneeEntiere) {
                $statut = 'absent_justifie';
            }
        }

        Presence::create([
            'employe_id' => $employe->id,
            'date_presence' => today(),
            'statut_pointage' => $statut,
        ]);
    }

    
    private function estWeekend(Carbon $date = null): bool
    {
        $date = $date ?? now();

        return $date->isSaturday() || $date->isSunday();
    }

    private function recupererJourFerieDuJour()
    {
        return JourFerie::whereDate('date_ferie', today())->first();
    }


    private function recupererCongeActif($employe)
    {
        return Demande::with('conge')
            ->where('employe_id', $employe->id)
            ->where('type_demande', 'conge')
            ->get()
            ->first(function ($demande) {
                return $demande->conge
                    && Carbon::today()->between(
                        $demande->conge->date_debut,
                        $demande->conge->date_fin
                    );
            });
    }

    
    
    public function index(Request $request)
    {
        $employe = $request->user()->employe;
        $this->creerAbsenceAutomatiqueSiNecessaire($employe);

        $joursTravaillesMois = [];

        $presenceDuJour = null;
        $historiqueRecent = collect();
        $heuresSemaine = [0, 0, 0, 0, 0, 0, 0];
        $totalSemaineMinutes = 0;
        $totalMoisMinutes = 0;
        $joursTravaillesMois = [];

        if ($employe) {
            $presenceDuJour = Presence::where('employe_id', $employe->id)
                ->whereDate('date_presence', today())
                ->first();

            $historiqueRecent = Presence::where('employe_id', $employe->id)
                ->orderByDesc('date_presence')
                ->limit(3)
                ->get();

            $debutSemaine = now()->startOfWeek(Carbon::MONDAY);
            $finSemaine = now()->endOfWeek(Carbon::SUNDAY);

            $presencesSemaine = Presence::where('employe_id', $employe->id)
                ->whereBetween('date_presence', [$debutSemaine->toDateString(), $finSemaine->toDateString()])
                ->get();

            foreach ($presencesSemaine as $presence) {
                $indexJour = Carbon::parse($presence->date_presence)->dayOfWeekIso - 1; // 0 à 6
                $minutes = $presence->duree_minutes ?? 0;
                $heuresSemaine[$indexJour] = round($minutes / 60, 2);
                $totalSemaineMinutes += $minutes;
            }

            $presencesMois = Presence::where('employe_id', $employe->id)
                ->whereMonth('date_presence', now()->month)
                ->whereYear('date_presence', now()->year)
                ->get();

            $totalMoisMinutes = $presencesMois->sum('duree_minutes');

            $joursTravaillesMois = $presencesMois
                ->filter(function ($presence) {
                    return !empty($presence->heure_arrivee);
                })
                ->map(function ($presence) {
                    return \Carbon\Carbon::parse($presence->date_presence)->day;
                })
                ->unique()
                ->toArray();
        }

        $congesEnAttente = Demande::where('employe_id', $employe->id)
            ->where('type_demande', 'conge')
            ->where('statut', 'en_attente')
            ->count();

        $permissionsEnAttente = Demande::where('employe_id', $employe->id)
            ->where('type_demande', 'permission')
            ->where('statut', 'en_attente')
            ->count();

            
        $nombrePresents = Presence::where('employe_id', $employe->id)
            ->whereMonth('date_presence', now()->month)
            ->whereYear('date_presence', now()->year)
            ->whereIn('statut_pointage', ['present', 'termine'])
            ->count();

        $nombreRetards = Presence::where('employe_id', $employe->id)
            ->whereMonth('date_presence', now()->month)
            ->whereYear('date_presence', now()->year)
            ->where('statut_pointage', 'retard')
            ->count();

        $nombreAbsents = Presence::where('employe_id', $employe->id)
            ->whereMonth('date_presence', now()->month)
            ->whereYear('date_presence', now()->year)
            ->where('statut_pointage', 'absent')
            ->count();

        $nombreAbsencesJustifiees = Presence::where('employe_id', $employe->id)
            ->whereMonth('date_presence', now()->month)
            ->whereYear('date_presence', now()->year)
            ->where('statut_pointage', 'absent_justifie')
            ->count();



        $stats = [
            'semaine' => $this->formatMinutes($totalSemaineMinutes),
            'mois' => $this->formatMinutes($totalMoisMinutes),
            'conges_en_attente' => $congesEnAttente,
            'permissions_en_attente' => $permissionsEnAttente,
            'presents' => $nombrePresents,
            'retards' => $nombreRetards,
            'absents' => $nombreAbsents,
            'absences_justifiees' => $nombreAbsencesJustifiees,
        ];


        return view('employe.dashboard', compact(
            'employe',
            'presenceDuJour',
            'historiqueRecent',
            'heuresSemaine',
            'joursTravaillesMois',
            'stats'
        ));
    }

    private function formatMinutes(?int $minutes): string
    {
        $minutes = $minutes ?? 0;
        $heures = intdiv($minutes, 60);
        $reste = $minutes % 60;

        return "{$heures}h {$reste}min";
    }
}
