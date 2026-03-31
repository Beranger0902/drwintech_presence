<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employe;
use App\Models\Demande;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        //  Lorsque l'administrtateur accède au dashboard , on récupère les données nécessaire poor afficher les statistiques et les activités récentes
        $admin = $request->user();

        //  Le statistique globale
        $totalUsers = User::count();

        $totalEmployes = Employe::count();

        $demandesEnAttente = Demande::where('statut', 'en_attente')->count();

        $totalDemandes = Demande::count();

        // Les demandes réccentes

        $demandesRecentes = Demande::with('employe.user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($demande) {
                return [
                    'nom' => $demande->employe->user->name ?? '---',
                    'type' => ucfirst($demande->type_demande),
                    'statut' => $demande->statut,
                ];
            });


        // Les activités récentes (ex: creation de demande, modification de profil, etc.)
        $activitesRecentes = Demande::with('employe.user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($demande) {

                $type = $demande->type_demande === 'conge'
                    ? 'Demande de congé'
                    : 'Demande de permission';

                return [
                    'nom' => $demande->employe->user->name ?? '---',
                    'action' => $type,
                    'heure' => $demande->created_at->format('H:i'),
                ];
            });


        // Dnnées pour le calendrier (ex: mois en cours, nombre de jours, etc.)
        $mois = (int) $request->get('mois', now()->month);
        $annee = (int) $request->get('annee', now()->year);

        $dateCalendrier = Carbon::createFromDate($annee, $mois, 1);

        $calendarData = [
            'mois' => $dateCalendrier->translatedFormat('F'),
            'mois_numero' => $dateCalendrier->month,
            'annee' => $dateCalendrier->year,
            'jour_actuel' => now()->month === $dateCalendrier->month && now()->year === $dateCalendrier->year
                ? now()->day
                : null,
            'premier_jour_semaine' => $dateCalendrier->copy()->startOfMonth()->dayOfWeek,
            'nombre_jours' => $dateCalendrier->daysInMonth,
            'mois_precedent' => $dateCalendrier->copy()->subMonth()->month,
            'annee_precedente' => $dateCalendrier->copy()->subMonth()->year,
            'mois_suivant' => $dateCalendrier->copy()->addMonth()->month,
            'annee_suivante' => $dateCalendrier->copy()->addMonth()->year,
        ];

        // On retourne la vue du dashboard avec les données nécessaires pour afficher les statistiques, les activités récentes et le calendrier
        return view('admin.dashboard', compact(
            'admin',
            'totalUsers',
            'totalEmployes',
            'demandesEnAttente',
            'totalDemandes',
            'activitesRecentes',
            'demandesRecentes',
            'calendarData'
        ));
    }
}
