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
    // Crée une présence immédiatement si c'est un weekend, féié, congé ou permission couvrant toute la journée
    private function creerPresenceCasSpecialSiNecessaire($employe): void
    {
        if (!$employe) {
            return;
        }

        $presenceDuJour = Presence::where('employe_id', $employe->id)
            ->whereDate('date_presence', today())
            ->first();

        // Si une présence existe déjà, ne rien faire
        if ($presenceDuJour) {
            return;
        }

        // Vérifier weekend
        if ($this->estWeekend()) {
            Presence::create([
                'employe_id' => $employe->id,
                'date_presence' => today(),
                'statut_pointage' => 'weekend',
            ]);
            return;
        }

        // Vérifier jour férié
        $jourFerie = $this->recupererJourFerieDuJour();
        if ($jourFerie) {
            Presence::create([
                'employe_id' => $employe->id,
                'date_presence' => today(),
                'statut_pointage' => 'ferie',
            ]);
            return;
        }

        // Vérifier congé actif
        $conge = $this->recupererCongeActif($employe);
        if ($conge) {
            Presence::create([
                'employe_id' => $employe->id,
                'date_presence' => today(),
                'statut_pointage' => 'conge',
            ]);
            return;
        }

        // Vérifier permission approuvée couvrant toute la journée
        $demandePermission = Demande::with('permission')
            ->where('employe_id', $employe->id)
            ->where('type_demande', 'permission')
            ->where('statut', 'approuver')
            ->get()
            ->first(function ($demande) {
                return $demande->permission
                    && $demande->permission->date_permission
                    && Carbon::parse($demande->permission->date_permission)->isToday();
            });

        if ($demandePermission && $demandePermission->permission) {
            $permission = $demandePermission->permission;

            $heureDebutTravail = config('pointage.heure_debut', '08:30');
            $heureFinTravail = config('pointage.heure_fin', '18:30');

            $permissionJourneeEntiere = $permission->heure_debut <= $heureDebutTravail
                && $permission->heure_fin >= $heureFinTravail;

            if ($permissionJourneeEntiere) {
                Presence::create([
                    'employe_id' => $employe->id,
                    'date_presence' => today(),
                    'statut_pointage' => 'absent_justifie',
                ]);
                return;
            }
        }
    }

    // Cette méthode vérifie si l'employé a une présence pour aujourd'hui.
    // Si ce n'est pas le cas et que l'heure actuelle est passée après l'heure limite,
    // elle crée automatiquement une entrée de présence avec le statut "absent" ou un autre statut approprié (weekend, ferie, conge, absent_justifie) selon les conditions.
    private function creerAbsenceAutomatiqueSiNecessaire($employe): void
    {
        if (! $employe) {
            return;
        }

        $presenceDuJour = Presence::where('employe_id', $employe->id)
            ->whereDate('date_presence', today())
            ->first();

        // Cas 1: Si l'employé a pointé l'arrivée mais pas le départ, et on est après 20h, marquer comme absent
        if ($presenceDuJour && $presenceDuJour->heure_arrivee && !$presenceDuJour->heure_depart) {
            $heureMax = config('pointage.heure_max_depart');
            [$heure, $minute] = explode(':', $heureMax);
            $heureLimite = now()->copy()->setTime((int) $heure, (int) $minute, 0);

            if (now()->greaterThan($heureLimite)) {
                $presenceDuJour->update([
                    'statut_pointage' => 'absent',
                    'duree_minutes' => null,
                    'duree_normale' => null,
                    'heures_supplementaires' => null,
                ]);
            }
            return;
        }

        if ($presenceDuJour) {
            return;
        }

        // Récupérer l'heure de fin de travail à partir de la configuration et créer un objet Carbon pour l'heure limite d'enregistrement de l'absence

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


        // Si l'employé est marqué comme absent mais qu'il a une permission active pour aujourd'hui, on peut considérer que son absence est justifiée.
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

    // Cette méthode vérifie si la date donnée (ou la date actuelle si aucune n'est fournie) est un samedi ou un dimanche, indiquant ainsi un jour de week-end.
    private function estWeekend(Carbon $date = null): bool
    {
        $date = $date ?? now();

        return $date->isSaturday() || $date->isSunday();
    }

    // Cette méthode récupère le jour férié correspondant à la date actuelle en interrogeant la table "jour_feries" pour trouver une entrée dont la date correspond à aujourd'hui. 
    // Si un jour férié est trouvé, il est retourné ; sinon, la méthode retourne null.
    private function recupererJourFerieDuJour()
    {
        return JourFerie::whereDate('date_ferie', today())->first();
    }

// Cette méthode récupère la demande de congé active pour l'employé donné. 
// Elle interroge la table "demandes" pour trouver les demandes de type "conge" associées à l'employé, puis vérifie si l'une de ces demandes a une période de congé qui inclut la date actuelle. 
// Si une telle demande est trouvée, elle est retournée ; sinon, la méthode retourne null.
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

    
    // Cette méthode est responsable de l'affichage du tableau de bord de l'employé. 
    // Elle récupère les données nécessaires pour afficher les informations de présence, les statistiques et les demandes en attente.
    // Tout d'abord, elle appelle la méthode "creerAbsenceAutomatiqueSiNecessaire" pour s'assurer que les absences sont correctement enregistrées.
    // Ensuite, elle récupère la présence du jour, l'historique récent des présences, les heures travaillées pour la semaine et le mois en cours, ainsi que les jours travaillés du mois.
    // Elle calcule également les statistiques telles que le nombre de congés en attente, de permissions en attente, de jours présents, de retards, d'absences et d'absences justifiées pour le mois en cours.
    // Enfin, elle retourne la vue du tableau de bord de l'employé en passant toutes les données nécessaires à l'affichage.
    public function index(Request $request)
    {
        $employe = $request->user()->employe;
        $this->creerPresenceCasSpecialSiNecessaire($employe);
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
            ->where('statut_arrivee', 'present')
            ->count();

        $nombreRetards = Presence::where('employe_id', $employe->id)
            ->whereMonth('date_presence', now()->month)
            ->whereYear('date_presence', now()->year)
            ->where('statut_arrivee', 'retard')
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

        // Récupérer les jours fériés du mois
        $joursFeriesMois = JourFerie::whereMonth('date_ferie', now()->month)
            ->whereYear('date_ferie', now()->year)
            ->get()
            ->map(function ($jourFerie) {
                return Carbon::parse($jourFerie->date_ferie)->day;
            })
            ->toArray();



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
            'joursFeriesMois',
            'stats'
        ));
    }

    // Cette méthode prend un nombre de minutes en entrée et le formate en une chaîne de caractères affichant les heures et les minutes.
    private function formatMinutes(?int $minutes): string
    {
        $minutes = $minutes ?? 0;
        $heures = intdiv($minutes, 60);
        $reste = $minutes % 60;

        return "{$heures}h {$reste}min";
    }
}
