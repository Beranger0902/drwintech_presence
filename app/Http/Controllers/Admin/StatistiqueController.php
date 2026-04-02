<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Employe;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StatistiqueController extends Controller
{
    // Affiche la page de statistiques avec les données filtrées par date et département
    
     public function index(Request $request)
    {
        $admin = $request->user();

        $dateDebut = $request->filled('date_debut')
            ? Carbon::parse($request->date_debut)->startOfDay()
            : now()->startOfMonth();

        $dateFin = $request->filled('date_fin')
            ? Carbon::parse($request->date_fin)->endOfDay()
            : now()->endOfMonth();

        $departement = $request->get('departement');

        /*
         Base employés filtrés par département
        */
        $employesQuery = Employe::query();

        if ($departement) {
            $employesQuery->where('departement', $departement);
        }

        $employeIds = $employesQuery->pluck('id');

        /*
         Cartes statistiques du haut
        
        */
        $totalUtilisateurs = $departement
            ? User::whereHas('employe', function ($q) use ($departement) {
                $q->where('departement', $departement);
            })->count()
            : User::count();

        $totalConges = Demande::where('type_demande', 'conge')
            ->whereIn('employe_id', $employeIds)
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->count();

        $totalPermissions = Demande::where('type_demande', 'permission')
            ->whereIn('employe_id', $employeIds)
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->count();

        $totalActivitesRecentes = $this->calculerTotalActivitesRecentes(
            $employeIds,
            $dateDebut,
            $dateFin,
            $departement
        );

        /*
         Graphique : activités récentes par jour
         Congés + Permissions + Ajouts utilisateurs
        */
        $labels = [];
        $serieConges = [];
        $seriePermissions = [];
        $serieAjouts = [];

        $periode = collect();
        $cursor = $dateDebut->copy()->startOfDay();

        while ($cursor->lessThanOrEqualTo($dateFin)) {
            $periode->push($cursor->copy());
            $cursor->addDay();
        }

        foreach ($periode as $date) {
            $labels[] = $date->format('d/m');

            $serieConges[] = Demande::where('type_demande', 'conge')
                ->whereIn('employe_id', $employeIds)
                ->whereDate('created_at', $date->toDateString())
                ->count();

            $seriePermissions[] = Demande::where('type_demande', 'permission')
                ->whereIn('employe_id', $employeIds)
                ->whereDate('created_at', $date->toDateString())
                ->count();

            $serieAjouts[] = $departement
                ? Employe::where('departement', $departement)
                    ->whereDate('created_at', $date->toDateString())
                    ->count()
                : User::whereDate('created_at', $date->toDateString())->count();
        }

        /*
        | Diagramme circulaire : répartition des demandes
        */
        $repartitionDemandes = [
            'labels' => [
                'Congés approuvés',
                'Congés en attente',
                'Congés refusés',
                'Permissions approuvées',
                'Permissions en attente',
                'Permissions refusées',
            ],
            'values' => [
                Demande::where('type_demande', 'conge')
                    ->where('statut', 'approuver')
                    ->whereIn('employe_id', $employeIds)
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->count(),

                Demande::where('type_demande', 'conge')
                    ->where('statut', 'en_attente')
                    ->whereIn('employe_id', $employeIds)
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->count(),

                Demande::where('type_demande', 'conge')
                    ->where('statut', 'refuser')
                    ->whereIn('employe_id', $employeIds)
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->count(),

                Demande::where('type_demande', 'permission')
                    ->where('statut', 'approuve')
                    ->whereIn('employe_id', $employeIds)
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->count(),

                Demande::where('type_demande', 'permission')
                    ->where('statut', 'en_attente')
                    ->whereIn('employe_id', $employeIds)
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->count(),

                Demande::where('type_demande', 'permission')
                    ->where('statut', 'refuse')
                    ->whereIn('employe_id', $employeIds)
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->count(),
            ],
        ];

        /*
         Liste des départements pour le filtre
        */
        $departements = Employe::query()
            ->select('departement')
            ->whereNotNull('departement')
            ->distinct()
            ->orderBy('departement')
            ->pluck('departement');

        return view('admin.statistiques.index', compact(
            'admin',
            'dateDebut',
            'dateFin',
            'departement',
            'departements',
            'totalUtilisateurs',
            'totalActivitesRecentes',
            'totalConges',
            'totalPermissions',
            'labels',
            'serieConges',
            'seriePermissions',
            'serieAjouts',
            'repartitionDemandes'
        ));
    }


  // Méthode pour calculer le total des activités récentes (congés + permissions + ajouts utilisateurs)

    private function calculerTotalActivitesRecentes($employeIds, Carbon $dateDebut, Carbon $dateFin, ?string $departement = null): int
    {
        $totalConges = Demande::where('type_demande', 'conge')
            ->whereIn('employe_id', $employeIds)
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->count();

        $totalPermissions = Demande::where('type_demande', 'permission')
            ->whereIn('employe_id', $employeIds)
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->count();

        $totalAjouts = $departement
            ? Employe::where('departement', $departement)
                ->whereBetween('created_at', [$dateDebut, $dateFin])
                ->count()
            : User::whereBetween('created_at', [$dateDebut, $dateFin])->count();

        return $totalConges + $totalPermissions + $totalAjouts;
    }
}
