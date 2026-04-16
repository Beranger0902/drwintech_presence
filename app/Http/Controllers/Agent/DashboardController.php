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
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();

        /*
        
        | Cartes statistiques du haut
        
        */
        $presents = Presence::whereBetween('created_at', [ $startDate , $endDate])
            ->whereIn('statut_pointage', ['present', 'termine'])
            ->count();

        $retards = Presence::whereBetween('created_at', [ $startDate , $endDate])
            ->where('statut_pointage', 'retard')
            ->count();

        $absents = Presence::whereBetween('created_at', [ $startDate , $endDate])
            ->where('statut_pointage', 'absent')
            ->count();

        $totalPointages = Presence::whereBetween('created_at', [ $startDate , $endDate])
            ->count();

        /*
        
        | Pointages récents
        
        */
        $pointagesRecents = Presence::whereBetween('created_at', [ $startDate , $endDate])
            ->latest()
            ->take(3)
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
