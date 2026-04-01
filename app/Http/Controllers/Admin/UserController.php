<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Cette methode index est utilisée pour afficher la liste de utlisateurrs dans l'interface d'administration.
    // Elle prend en charge la recherche par différents champs, le filtrage par rôle et statut, et la pagination des résultats. 
    // Elle gère également l'affichage des détails d'un utilisateur sélectionné dans un modal.
     public function index(Request $request)
    {
        $admin = $request->user();

        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('statut')) {
            $query->where('actif', $request->statut === 'actif');
        }

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(8)->withQueryString();

        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'administrateur')->count();
        $totalAgents = User::where('role', 'agent_accueil')->count();
        $totalEmployes = User::where('role', 'employe')->count();

        $selectedUser = null;
        $openModal = null;

        if ($request->filled('view')) {
            $selectedUser = User::find($request->view);
            $openModal = $selectedUser ? 'view' : null;
        }

        if ($request->filled('edit')) {
            $selectedUser = User::find($request->edit);
            $openModal = $selectedUser ? 'edit' : null;
        }

        if ($request->filled('delete')) {
            $selectedUser = User::find($request->delete);
            $openModal = $selectedUser ? 'delete' : null;
        }

        return view('admin.utilisateurs.index', compact(
            'admin',
            'users',
            'totalUsers',
            'totalAdmins',
            'totalAgents',
            'totalEmployes',
            'selectedUser',
            'openModal'
        ));
    }

    // Cette methode store est utilisée pour ajouter un nouvel utilisateur à la base de données.
    // Elle valide les données d'entrée, crée un nouvel enregistrement dans la table des utilisateurs, puis redirige vers la liste des utilisateurs avec un message de succès. 
    // En cas d'ereur, elle redirige avec les messages d'erreur appropriés.

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['administrateur', 'agent_accueil', 'employe'])],
            'actif' => ['required', 'boolean'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'actif' => (bool) $validated['actif'],
        ]);

        return redirect()
            ->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur ajouté avec succès.');
    }

    // Cette methode update est utilisée pour modifier les informations d'un utilisateur existant.
    // Elle valide les données d'entrée et met à jour les informations de l'utilisateur dans la base de données. 
    // Si la mise à jour est réussie, elle redirige vers la liste des utilisateurs avec un message de succès. 
    // En cas d'erreur, elle redirige avec les messages d'erreur appropriés.

    public function update(Request $request, User $utilisateur)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $utilisateur->id],
            'role' => ['required', Rule::in(['administrateur', 'agent_accueil', 'employe'])],
            'actif' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'actif' => (bool) $validated['actif'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $utilisateur->update($data);

        return redirect()
            ->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    // Cette méthode destroy est utilisée pour supprimer un utilisateur de la base de données.
    // Elle prend en paramètre l'utilisateur à supprimer, effectue la suppression, puis redirige vers la liste des utilisateurs avec un message de succès.

    public function destroy(User $utilisateur)
    {
        $utilisateur->delete();

        return redirect()
            ->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
