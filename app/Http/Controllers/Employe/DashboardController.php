<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
     public function index(Request $request)
    {
        $employe = $request->user()->employe;

        $jourTravaillesMois = [];

        $presenceDuJour = null;
        $historiqueRecent = collect();
        $heuresSemaine = [0, 0, 0, 0, 0, 0, 0];
        $totalSemaineMinutes = 0;
        $totalMoisMinutes = 0;
        $jourTravaillesMois = [];

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

        $stats = [
            'semaine' => $this->formatMinutes($totalSemaineMinutes),
            'mois' => $this->formatMinutes($totalMoisMinutes),
            'conges_en_attente' => 0,
            'permissions_en_attente' => 0,
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
