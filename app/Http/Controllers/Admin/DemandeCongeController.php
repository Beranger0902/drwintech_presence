<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use Illuminate\Http\Request;

class DemandeCongeController extends Controller
{
    // Cette méthode index est utilisée pour afficher la liste des demandes de congé dans l'interface d'administration.
    // Elle prend en charge la recherche par différents champs, le filtrage par statut, et la pagination des résultats.
    public function index(Request $request)
    {
        $admin = $request->user();

        $query = Demande::with(['employe.user', 'conge'])
            ->where('type_demande', 'conge');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

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

        // Récupération des demandes de congé avec pagination, triées par date de creation décroissante, et en conservant les paramètres de requête pour la pagination.
        $conges = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalConges = Demande::where('type_demande', 'conge')->count();
        $congesEnAttente = Demande::where('type_demande', 'conge')->where('statut', 'en_attente')->count();
        $congesApprouves = Demande::where('type_demande', 'conge')->where('statut', 'approuver')->count();
        $congesRefuses = Demande::where('type_demande', 'conge')->where('statut', 'refuser')->count();

        $selectedConge = null;
        $openModal = null;

        // Si une demande de congé spécifique est sélectionnée pour être affichée (indiquée par le paramètre "view" dans la requête),
        // elle est récupérée avec ses relations employé et congé, et une variable est définie pour indiquer que le modal de visualisation doit être ouvert.
        if ($request->filled('view')) {
            $selectedConge = Demande::with(['employe.user', 'conge'])->where('type_demande', 'conge')->find($request->view);
            $openModal = $selectedConge ? 'view' : null;
        }

        return view('admin.demandes.conges.index', compact(
            'admin',
            'conges',
            'totalConges',
            'congesEnAttente',
            'congesApprouves',
            'congesRefuses',
            'selectedConge',
            'openModal'
        ));
    }

    // Ces méthodes approve et refuse sont utilisées pour approuver ou refuser une demande de congé spécifique. 
    // Elles vérifient d'abord que la demande est bien de type "conge" avant de mettre à jour son statut en conséquence.

    public function approuver(Demande $demande)
    {
        if ($demande->type_demande !== 'conge') {
            abort(404);
        }

        $demande->update([
            'statut' => 'approuver',
        ]);

        return redirect()
            ->route('admin.demandes.conges.index')
            ->with('success', 'La demande de congé a été approuvée avec succès.');
    }

    // Ces méthodes approve et refuse sont utilisées pour approuver ou refuser une demande de congé spécifique. 
    // Elles vérifient d'abord que la demande est bien de type "conge" avant de mettre à jour son statut en conséquence.

    public function refuser(Demande $demande)
    {
        if ($demande->type_demande !== 'conge') {
            abort(404);
        }

        $demande->update([
            'statut' => 'refuser',
        ]);

        return redirect()
            ->route('admin.demandes.conges.index')
            ->with('success', 'La demande de congé a été refusée.');
    }
}
