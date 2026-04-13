<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\Employe;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresenceController extends Controller
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

        $departement = $request->get('departement');
        $statut = $request->get('statut');
        $search = trim((string) $request->get('search'));

        $query = Presence::with(['employe.user'])
            ->whereBetween('date_presence', [$dateDebut->toDateString(), $dateFin->toDateString()]);

        if (!empty($departement)) {
            $query->whereHas('employe', function ($q) use ($departement) {
                $q->where('departement', $departement);
            });
        }

        if (!empty($statut)) {
            $query->where('statut_pointage', $statut);
        }

        if (!empty($search)) {
            $query->whereHas('employe', function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('matricule', 'like', "%{$search}%")
                    ->orWhereRaw("CONCAT(prenom, ' ', nom) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("CONCAT(nom, ' ', prenom) LIKE ?", ["%{$search}%"]);
            });
        }

        $presences = $query
            ->orderByDesc('date_presence')
            ->orderByDesc('heure_arrivee')
            ->paginate(8)
            ->withQueryString();

        $totalPresences = (clone $query)->count();

        $departements = Employe::query()
            ->select('departement')
            ->whereNotNull('departement')
            ->distinct()
            ->orderBy('departement')
            ->pluck('departement');

        $selectedPresence = null;
        $openModal = null;

        if ($request->filled('view')) {
            $selectedPresence = Presence::with(['employe.user'])
                ->find($request->view);

            $openModal = $selectedPresence ? 'view' : null;
        }

        return view('agent.presences.index', compact(
            'agent',
            'presences',
            'totalPresences',
            'departements',
            'dateDebut',
            'dateFin',
            'departement',
            'statut',
            'search',
            'selectedPresence',
            'openModal'
        ));
    }
}
