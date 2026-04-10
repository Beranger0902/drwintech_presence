<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    // Cette méthode affiche la liste des demandes de permission de l'employé connecté. 
    // Elle récupère les demandes de permission associées à l'employé, les trie par date de soumission et les pagine pour une meilleure lisibilité. 
    // Ensuite, elle retourne la vue correspondante en passant les données nécessaires pour l'affichage.
    public function index(Request $request)
    {
        $employe = $request->user()->employe;

        $demandesPermission = Demande::with('permission')
            ->where('employe_id', $employe->id)
            ->where('type_demande', 'permission')
            ->latest()
            ->paginate(10);

        return view('employe.demandes.permissions.index', compact('employe', 'demandesPermission'));
    }


    // Cette méthode traite la soumission d'une nouvelle demande de permission.
    // Elle valide les données reçues du formulaire,
    // crée une nouvelle entrée dans la table "demandes" pour enregistrer la demande de permission,
    // puis crée une entrée correspondante dans la table "permissions" pour stocker les détails spécifiques de la permission (date, heure de début, heure de fin).
    // Enfin, elle redirige l'utilisateur vers la page précédente avec un message de succès indiquant que la permission a été envoyée.
    public function store(Request $request)
    {
        $employe = $request->user()->employe;

        if (! $employe) {
            abort(404, "Employe introuvable.");
        }

        // Point 1: Vérifier si les demandes sont bloquées
        if ($employe->demandes_bloquees) {
            return redirect()
                ->route('employe.demandes.permissions.index')
                ->withErrors(["message" => "Vos demandes sont actuellement bloquees. Veuillez contacter l'administrateur pour debloquer l'acces."]);
        }

        $validated = $request->validate([
            'date_permission' => ['required', 'date'],
            'heure_debut' => ['required'],
            'heure_fin' => ['required', 'after:heure_debut'],
            'observation' => ['nullable'],
        ]);

        // Point 2: Vérifier s'il existe une demande (congé ou permission) en attente ou approuvée
        $demandeActive = Demande::where('employe_id', $employe->id)
            ->whereIn('statut', ['en_attente', 'approuver', 'approuve'])
            ->where(function ($query) use ($request) {
                // Pour les congés
                $query->where(function ($q) use ($request) {
                    $q->where('type_demande', 'conge')
                        ->whereHas('conge', function ($sub) use ($request) {
                            $sub->where('date_debut', '<=', $request->date_permission)
                                ->where('date_fin', '>=', $request->date_permission);
                        });
                })
                // Pour les permissions
                ->orWhere(function ($q) use ($request) {
                    $q->where('type_demande', 'permission')
                        ->whereHas('permission', function ($sub) use ($request) {
                            $sub->where('date_permission', '=', $request->date_permission);
                        });
                });
            })
            ->first();

        if ($demandeActive) {
            return redirect()
                ->route('employe.demandes.permissions.index')
                ->withErrors(["message" => "Vous avez deja une demande en attente ou approuvee. Veuillez attendre que celle-ci soit traitee."])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $demande = Demande::create([
                'employe_id' => $employe->id,
                'type_demande' => 'permission',
                'observation' => $validated['observation'],
                'date_soumission' => now(),
                'statut' => 'en_attente',
            ]);

            Permission::create([
                'demande_id' => $demande->id,
                'date_permission' => $validated['date_permission'],
                'heure_debut' => $validated['heure_debut'],
                'heure_fin' => $validated['heure_fin'],
            ]);

            DB::commit();

            return redirect()
                ->route('employe.demandes.permissions.index')
                ->with('success', 'Permission envoyee avec succes.');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
