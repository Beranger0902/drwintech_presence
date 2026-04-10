<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer l'employé connecté
        $admin = $request->user();

        $query = Demande::with(['employe.user', 'permission'])
            ->where('type_demande', 'permission');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Si une recherche est effectuée, filtrer les demandes de permission en fonction des champs de l'employé (nom, prénom, matricule), de l'email de l'utilisateur associé, ou de l'observation de la demande.
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('employe', function ($sub) use ($search) {
                    $sub->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('matricule', 'like', "%{$search}%");
                })->orWhereHas('employe.user', function ($sub) use ($search) {
                    $sub->where('email', 'like', "%{$search}%");
                })->orWhere('observation', 'like', "%{$search}%");
            });
        }


        // Récupération des demandes de permission avec pagination, triées par date de creation décroissante, et en conservant les paramètres de requête pour la pagination.

        $permissions = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

            // Calcul des statistiques pour les demandes de permission : total, en attente, approuvées, refusées.
        $totalPermissions = Demande::where('type_demande', 'permission')->count();
        $permissionsEnAttente = Demande::where('type_demande', 'permission')
            ->where('statut', 'en_attente')
            ->count();
        $permissionsApprouvees = Demande::where('type_demande', 'permission')
            ->where('statut', 'approuve')
            ->count();
        $permissionsRefusees = Demande::where('type_demande', 'permission')
            ->where('statut', 'refuse')
            ->count();

        $selectedPermission = null;
        $openModal = null;

            // Si une demande de permission spécifique est sélectionnée pour être affichée (indiquée par le paramètre "view" dans la requête),

        if ($request->filled('view')) {
            $selectedPermission = Demande::with(['employe.user', 'permission'])
                ->where('type_demande', 'permission')
                ->find($request->view);

            $openModal = $selectedPermission ? 'view' : null;
        }

        return view('admin.demandes.permissions.index', compact(
            'admin',
            'permissions',
            'totalPermissions',
            'permissionsEnAttente',
            'permissionsApprouvees',
            'permissionsRefusees',
            'selectedPermission',
            'openModal'
        ));
    }

    // Méthodes pour approuver ou refuser une demande de permission

    public function approve(Demande $demande)
    {
        if ($demande->type_demande !== 'permission') {
            abort(404);
        }

        $demande->update([
            'statut' => 'approuve',
        ]);

        // Réinitialiser le compteur de refus et débloquer l'employé
        $demande->employe->update([
            'refusals_count' => 0,
            'demandes_bloquees' => false,
        ]);

        return redirect()
            ->route('admin.demandes.permissions.index')
            ->with('success', 'La demande de permission a été approuvée avec succès.');
    }


    // Méthode pour refuser une demande de permission
    public function refuse(Demande $demande)
    {
        if ($demande->type_demande !== 'permission') {
            abort(404);
        }

        $demande->update([
            'statut' => 'refuse',
        ]);

        // Incrémenter le compteur de refus
        $employe = $demande->employe;
        $employe->refusals_count++;

        // Bloquer après 3 refus
        if ($employe->refusals_count >= 3) {
            $employe->demandes_bloquees = true;
        }

        $employe->save();

        return redirect()
            ->route('admin.demandes.permissions.index')
            ->with('success', 'La demande de permission a été refusée.');
    }

    public function debloquer(Demande $demande)
    {
        if ($demande->type_demande !== 'permission') {
            abort(404);
        }

        // Réinitialiser le compteur de refus et débloquer l'employé
        $demande->employe->update([
            'refusals_count' => 0,
            'demandes_bloquees' => false,
        ]);

        return redirect()
            ->route('admin.demandes.permissions.index')
            ->with('success', "L'employé a été débloqué avec succès.");
    }
}
