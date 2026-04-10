<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\Conge;
use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CongeController extends Controller
{
    // Cette méthode index est utilisée pour afficher la liste des demandes de congé d'un employé dans l'interface de l'employé.
    // Elle récupère les demandes de congé associées à l'employé connecté, les pagine, et les passe à la vue pour affichage.
     public function index(Request $request)
    {
        $employe = $request->user()->employe;

        if (! $employe) {
            abort(404, 'Employé introuvable.');
        }

        $demandesConge = Demande::with('conge')
            ->where('employe_id', $employe->id)
            ->where('type_demande', 'conge')
            ->latest()
            ->paginate(10);

        return view('employe.demandes.conges.index', compact('employe', 'demandesConge'));
    }

    // Cette méthode store est utilisée pour soumettre une nouvelle demande de congé par un employé.
    // Elle valide les données d'entrée, crée une nouvelle demande de congé et un enregistrement de congé associé dans la base de données, puis redirige vers la liste des demandes de congé avec un message de succès. 
    // En cas d'erreur, elle gère la transaction et redirige avec les messages d'erreur appropriés.

    public function store(Request $request)
    {
        $employe = $request->user()->employe;

        if (! $employe) {
            abort(404, "Employe introuvable.");
        }

        // Point 1: Vérifier si les demandes sont bloquées
        if ($employe->demandes_bloquees) {
            return redirect()
                ->route('employe.demandes.conges.index')
                ->withErrors(["message" => "Vos demandes sont actuellement bloquees. Veuillez contacter l\'administrateur pour debloquer l\'acces."]);
        }

        $validated = $request->validate([
            'type_conge' => ['required', 'string', 'max:255'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date', 'after_or_equal:date_debut'],
            'observation' => ['nullable', 'string'],
            'piece_jointe' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        // Point 2: Vérifier s'il existe une demande (congé ou permission) en attente ou approuvée
        $demandeActive = Demande::where('employe_id', $employe->id)
            ->whereIn('statut', ['en_attente', 'approuver', 'approuve'])
            ->where(function ($query) use ($request) {
                // Pour les congés
                $query->where(function ($q) use ($request) {
                    $q->where('type_demande', 'conge')
                        ->whereHas('conge', function ($sub) use ($request) {
                            $sub->where('date_fin', '>=', $request->date_debut);
                        });
                })
                // Pour les permissions
                ->orWhere(function ($q) use ($request) {
                    $q->where('type_demande', 'permission')
                        ->whereHas('permission', function ($sub) use ($request) {
                            $sub->where('date_permission', '>=', $request->date_debut)
                                ->where('date_permission', '<=', $request->date_fin);
                        });
                });
            })
            ->first();

        if ($demandeActive) {
            return redirect()
                ->route('employe.demandes.conges.index')
                ->withErrors(["message" => "Vous avez deja une demande en attente ou approuvee. Veuillez attendre que celle-ci soit traitee."])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $pieceJointePath = null;

            if ($request->hasFile('piece_jointe')) {
                $pieceJointePath = $request->file('piece_jointe')->store('conges', 'public');
            }

            $demande = Demande::create([
                'employe_id' => $employe->id,
                'type_demande' => 'conge',
                'motif' => $validated['type_conge'],
                'observation' => $validated['observation'] ?? null,
                'date_soumission' => now(),
                'statut' => 'en_attente',
            ]);

            Conge::create([
                'demande_id' => $demande->id,
                'date_debut' => $validated['date_debut'],
                'date_fin' => $validated['date_fin'],
                'type_conge' => $validated['type_conge'],
                'piece_jointe' => $pieceJointePath,
            ]);

            DB::commit();

            return redirect()
                ->route('employe.demandes.conges.index')
                ->with('success', 'Demande de conge soumise avec succes.');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
