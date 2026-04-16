<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Employe;
use App\Models\Presence;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RapportController extends Controller
{
   public function index(Request $request)
    {
        $agent = $request->user();

        $dateDebut = $request->filled('date_debut')
            ? Carbon::parse($request->date_debut)->startOfDay()
            : now()->startOfMonth();

        $dateFin = $request->filled('date_fin')
            ? Carbon::parse($request->date_fin)->endOfDay()
            : now()->endOfMonth();

        $employeId = $request->get('employe_id');
        $generate = $request->get('generate');

        $employes = Employe::query()
            ->orderBy('prenom')
            ->orderBy('nom')
            ->get();

        $rapportsQuery = Presence::with(['employe.user'])
            ->whereBetween('date_presence', [
                $dateDebut->toDateString(),
                $dateFin->toDateString(),
            ]);

        if (!empty($employeId)) {
            $rapportsQuery->where('employe_id', $employeId);
        }

        $rapports = $rapportsQuery
            ->orderByDesc('date_presence')
            ->orderByDesc('heure_arrivee')
            ->get();

        $totalRapports = $rapports->count();

        $rapportPresence = null;
        $rapportWork = null;

        if ($generate === 'presence') {
            $rapportPresence = $this->buildPresenceReport($dateDebut, $dateFin, $employeId);
        }

        if ($generate === 'work') {
            $rapportWork = $this->buildWorkReport($dateDebut, $dateFin, $employeId);
        }

        return view('agent.rapports.index', compact(
            'agent',
            'employes',
            'rapports',
            'totalRapports',
            'dateDebut',
            'dateFin',
            'employeId',
            'rapportPresence',
            'rapportWork'
        ));
    }

    
    public function exportPdf(Request $request)
    {
        $dateDebut = Carbon::parse($request->date_debut)->startOfDay();
        $dateFin = Carbon::parse($request->date_fin)->endOfDay();
        $employeId = $request->employe_id;

        $rapportPresence = $this->buildPresenceReport($dateDebut, $dateFin, $employeId);

        $employe = null;
        if ($employeId) {
            $employe = Employe::find($employeId);
        }

        $pdf = Pdf::loadView('agent.rapports.export.pdf', [
            'rapport' => $rapportPresence,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'employe' => $employe
        ]);

        return $pdf->download('rapport.pdf');
    }

    private function buildPresenceReport(Carbon $dateDebut, Carbon $dateFin, ?string $employeId = null): array
    {
        $query = Presence::with(['employe.user'])
            ->whereBetween('date_presence', [
                $dateDebut->toDateString(),
                $dateFin->toDateString(),
            ]);

        if (!empty($employeId)) {
            $query->where('employe_id', $employeId);
        }

        $presences = $query->orderBy('date_presence')->get();

        $stats = [
            'presents' => $presences->whereIn('statut_pointage', ['present', 'termine'])->count(),
            'retards' => $presences->where('statut_pointage', 'retard')->count(),
            'absents' => $presences->where('statut_pointage', 'absent')->count(),
            'conges' => $presences->where('statut_pointage', 'conge')->count(),
        ];

        $labels = [];
        $seriePresents = [];
        $serieRetards = [];
        $serieAbsents = [];
        $serieConges = [];

        $cursor = $dateDebut->copy()->startOfDay();

        while ($cursor->lessThanOrEqualTo($dateFin)) {
            $jour = $cursor->toDateString();

            $labels[] = $cursor->format('d/m');

            $presencesDuJour = $presences->filter(function ($presence) use ($jour) {
                return Carbon::parse($presence->date_presence)->toDateString() === $jour;
            });

            $seriePresents[] = $presencesDuJour->whereIn('statut_pointage', ['present', 'termine'])->count();
            $serieRetards[] = $presencesDuJour->where('statut_pointage', 'retard')->count();
            $serieAbsents[] = $presencesDuJour->where('statut_pointage', 'absent')->count();
            $serieConges[] = $presencesDuJour->where('statut_pointage', 'conge')->count();

            $cursor->addDay();
        }

        $details = $presences->sortByDesc('date_presence')->map(function ($presence) {
            $statutLabel = match ($presence->statut_pointage) {
                'present', 'termine' => 'Présent',
                'retard' => 'En retard',
                'absent' => 'Absent',
                'conge' => 'En congé',
                'ferie' => 'Jour férié',
                'weekend' => 'Week-end',
                'absent_justifie' => 'Permission',
                default => ucfirst(str_replace('_', ' ', $presence->statut_pointage ?? 'inconnu')),
            };

            return [
                'nom' => trim(($presence->employe?->prenom ?? '') . ' ' . ($presence->employe?->nom ?? '')),
                'date' => $presence->date_presence,
                'heure_arrivee' => $presence->heure_arrivee,
                'heure_depart' => $presence->heure_depart,
                'statut' => $statutLabel,
                'statut_code' => $presence->statut_pointage,
            ];
        })->values();

        return [
            'stats' => $stats,
            'labels' => $labels,
            'seriePresents' => $seriePresents,
            'serieRetards' => $serieRetards,
            'serieAbsents' => $serieAbsents,
            'serieConges' => $serieConges,
            'pieLabels' => ['Présents', 'Retards', 'Absents', 'En congé'],
            'pieValues' => [
                $stats['presents'],
                $stats['retards'],
                $stats['absents'],
                $stats['conges'],
            ],
            'details' => $details,
        ];
    }

    private function buildWorkReport(Carbon $dateDebut, Carbon $dateFin, ?string $employeId = null): array
    {
        $query = Presence::with(['employe.user'])
            ->whereBetween('date_presence', [
                $dateDebut->toDateString(),
                $dateFin->toDateString(),
            ]);

        if (!empty($employeId)) {
            $query->where('employe_id', $employeId);
        }

        $presences = $query->orderBy('date_presence')->get();

        $grouped = $presences->groupBy('employe_id');

        $labels = [];
        $serieNormales = [];
        $serieSupp = [];
        $details = [];

        $totalMinutes = 0;
        $totalMinutesNormales = 0;
        $totalMinutesSupp = 0;

        foreach ($grouped as $empId => $items) {
            $employe = $items->first()->employe;

            if (!$employe) {
                continue;
            }

            $minutesEmploye = 0;
            $minutesNormalesEmploye = 0;
            $minutesSuppEmploye = 0;

            foreach ($items as $presence) {
                if (!$presence->heure_arrivee || !$presence->heure_depart) {
                    continue;
                }

                $arrivee = Carbon::parse($presence->heure_arrivee);
                $depart = Carbon::parse($presence->heure_depart);

                if ($depart->lessThanOrEqualTo($arrivee)) {
                    continue;
                }

                $minutes = $depart->diffInMinutes($arrivee);
                $minutesNormales = min($minutes, 480);
                $minutesSupp = max($minutes - 480, 0);

                $minutesEmploye += $minutes;
                $minutesNormalesEmploye += $minutesNormales;
                $minutesSuppEmploye += $minutesSupp;
            }

            $labels[] = trim(($employe->prenom ?? '') . ' ' . ($employe->nom ?? ''));
            $serieNormales[] = round($minutesNormalesEmploye / 60, 2);
            $serieSupp[] = round($minutesSuppEmploye / 60, 2);

            $details[] = [
                'nom' => trim(($employe->prenom ?? '') . ' ' . ($employe->nom ?? '')),
                'periode_debut' => $dateDebut->format('d/m/Y'),
                'periode_fin' => $dateFin->format('d/m/Y'),
                'heures_normales' => $this->formatHoursLabel($minutesNormalesEmploye),
                'heures_supp' => $this->formatHoursLabel($minutesSuppEmploye),
                'total_heures' => $this->formatHoursLabel($minutesEmploye),
            ];

            $totalMinutes += $minutesEmploye;
            $totalMinutesNormales += $minutesNormalesEmploye;
            $totalMinutesSupp += $minutesSuppEmploye;
        }

        return [
            'stats' => [
                'heures_travaillees' => $this->formatHoursLabel($totalMinutes),
                'heures_normales' => $this->formatHoursLabel($totalMinutesNormales),
                'heures_supp' => $this->formatHoursLabel($totalMinutesSupp),
                'heures_supp_brut' => round($totalMinutesSupp / 60, 2),
            ],
            'labels' => $labels,
            'serieNormales' => $serieNormales,
            'serieSupp' => $serieSupp,
            'details' => $details,
        ];
    }

    private function formatHoursLabel(int $minutes): string
    {
        $heures = floor($minutes / 60);
        $mins = $minutes % 60;

        return $heures . ' h ' . str_pad($mins, 2, '0', STR_PAD_LEFT) . ' min';
    }
}
