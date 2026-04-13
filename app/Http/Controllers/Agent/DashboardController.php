<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
     public function index(Request $request)
    {
        $agent = $request->user();
        $today = Carbon::today();

        /*
        
        | Cartes statistiques du haut
        
        */
        $presents = Presence::whereDate('date_presence', $today)
            ->whereIn('statut_pointage', ['present', 'termine'])
            ->count();

        $retards = Presence::whereDate('date_presence', $today)
            ->where('statut_pointage', 'retard')
            ->count();

        $absents = Presence::whereDate('date_presence', $today)
            ->where('statut_pointage', 'absent')
            ->count();

        $totalPointages = Presence::whereDate('date_presence', $today)
            ->count();

        /*
        
        | Pointages récents
        
        */
        $pointagesRecents = Presence::with(['employe.user'])
            ->whereDate('date_presence', $today)
            ->orderByDesc('updated_at')
            ->take(5)
            ->get();

        /*
        
        | Activités du jour
        */
        $activitesDuJour = collect();

        foreach ($pointagesRecents as $presence) {
            $nomEmploye = trim(($presence->employe?->prenom ?? '') . ' ' . ($presence->employe?->nom ?? ''));

            if ($presence->heure_arrivee) {
                $activitesDuJour->push([
                    'type' => $presence->statut_pointage === 'retard' ? 'retard' : 'arrivee',
                    'message' => $nomEmploye . ' a pointé à ' . Carbon::parse($presence->heure_arrivee)->format('H:i'),
                    'temps' => Carbon::parse($presence->updated_at)->format('H:i'),
                ]);
            }

            if ($presence->statut_pointage === 'absent') {
                $activitesDuJour->push([
                    'type' => 'absent',
                    'message' => $nomEmploye . ' est absent(e)',
                    'temps' => Carbon::parse($presence->updated_at)->format('H:i'),
                ]);
            }
        }

        $activitesDuJour = $activitesDuJour->take(6);

        return view('agent.dashboard', compact(
            'agent',
            'presents',
            'retards',
            'absents',
            'totalPointages',
            'pointagesRecents',
            'activitesDuJour'
        ));
    }
}
