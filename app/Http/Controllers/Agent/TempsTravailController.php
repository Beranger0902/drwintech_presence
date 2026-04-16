<?php
/*
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Presence;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TempsTravailController extends Controller
{
     public function index(Request $request)
    {
        $dateDebut = $request->date_debut ?? now()->startOfMonth()->toDateString();
        $dateFin = $request->date_fin ?? now()->endOfMonth()->toDateString();
        $employeId = $request->employe_id;
        $search = $request->search;

        //  Liste employés
        $employesQuery = Employe::query();

        if (!empty($search)) {
            $employesQuery->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhereRaw("CONCAT(prenom,' ',nom) LIKE ?", ["%{$search}%"]);
            });
        }

        if (!empty($employeId)) {
            $employesQuery->where('id', $employeId);
        }

        $employes = $employesQuery->paginate(10);

        //  Calcul temps travail
        $data = [];

        foreach ($employes as $employe) {

            $presences = Presence::where('employe_id', $employe->id)
                ->whereBetween('date_presence', [$dateDebut, $dateFin])
                ->whereNotNull('heure_arrivee')
                ->whereNotNull('heure_depart')
                ->get();

            $totalMinutes = 0;
            $suppMinutes = 0;

            foreach ($presences as $p) {

                $debut = Carbon::parse($p->heure_arrivee);
                $fin = Carbon::parse($p->heure_depart);

                $minutes = $fin->diffInMinutes($debut);
                $totalMinutes += $minutes;

                //  heures supp (au-delà de 8h)
                if ($minutes > 480) {
                    $suppMinutes += ($minutes - 480);
                }
            }

            $data[] = [
                'employe' => $employe,
                'total_heures' => floor($totalMinutes / 60),
                'heures_service' => floor(($totalMinutes - $suppMinutes) / 60),
                'heures_supp' => floor($suppMinutes / 60),
            ];
        }

        //  Modal (voir détail)
        $selectedEmploye = null;
        $details = collect();
        $totalGlobal = 0;
        $suppGlobal = 0;

        if ($request->has('view')) {

            $selectedEmploye = Employe::find($request->view);

            $presences = Presence::where('employe_id', $selectedEmploye->id)
                ->whereBetween('date_presence', [$dateDebut, $dateFin])
                ->orderBy('date_presence', 'desc')
                ->get();

            foreach ($presences as $p) {

                $temps = null;

                if ($p->heure_arrivee && $p->heure_depart) {

                    $debut = Carbon::parse($p->heure_arrivee);
                    $fin = Carbon::parse($p->heure_depart);

                    $minutes = $fin->diffInMinutes($debut);
                    $totalGlobal += $minutes;

                    if ($minutes > 480) {
                        $suppGlobal += ($minutes - 480);
                    }

                    $temps = floor($minutes / 60) . ' h ' . ($minutes % 60) . ' min';
                }

                $details->push([
                    'date' => $p->date_presence,
                    'arrivee' => $p->heure_arrivee,
                    'depart' => $p->heure_depart,
                    'temps' => $temps,
                ]);
            }
        }

        return view('agent.temps_travail.index', compact(
            'data',
            'employes',
            'dateDebut',
            'dateFin',
            'selectedEmploye',
            'details',
            'totalGlobal',
            'suppGlobal'
        ));
    }
}
    */
