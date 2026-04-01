<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Employe;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeController extends Controller
{
    // Cette méthode index est utlisée pour afficher la liste des employés dans l'interface d'administration.
    // Elle prend en charge la recherche par différents champs, le filtrage par département, et la pagination des résultats.
     public function index(Request $request)
    {
        $admin = $request->user();

        $query = Employe::with('user')->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('matricule', 'like', "%{$search}%")
                    ->orWhere('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('poste', 'like', "%{$search}%")
                    ->orWhere('departement', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($sub) use ($search) {
                        $sub->where('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('departement')) {
            $query->where('departement', $request->departement);
        }

        $employes = $query->paginate(8)->withQueryString();

        $employes->getCollection()->transform(function ($employe) {
            $employe->statut_calcule = $this->determinerStatutEmploye($employe);
            return $employe;
        });

        $totalEmployes = Employe::count();
        $enregistres = Employe::count();
        $enConge = Employe::get()->filter(fn ($employe) => $this->determinerStatutEmploye($employe) === 'En congé')->count();
        $enPermission = Employe::get()->filter(fn ($employe) => $this->determinerStatutEmploye($employe) === 'En permission')->count();

        $selectedEmploye = null;
        $openModal = null;

        if ($request->filled('view')) {
            $selectedEmploye = Employe::with('user')->find($request->view);
            if ($selectedEmploye) {
                $selectedEmploye->statut_calcule = $this->determinerStatutEmploye($selectedEmploye);
                $openModal = 'view';
            }
        }

        if ($request->filled('edit')) {
            $selectedEmploye = Employe::with('user')->find($request->edit);
            if ($selectedEmploye) {
                $selectedEmploye->statut_calcule = $this->determinerStatutEmploye($selectedEmploye);
                $openModal = 'edit';
            }
        }

        if ($request->filled('delete')) {
            $selectedEmploye = Employe::with('user')->find($request->delete);
            if ($selectedEmploye) {
                $selectedEmploye->statut_calcule = $this->determinerStatutEmploye($selectedEmploye);
                $openModal = 'delete';
            }
        }

        return view('admin.employes.index', compact(
            'admin',
            'employes',
            'totalEmployes',
            'enregistres',
            'enConge',
            'enPermission',
            'selectedEmploye',
            'openModal'
        ));
    }

    //Cette methode store est utilisée pour ajouter un nouvel employé à la base de données. 
    // Elle valide les données d'entrée, vérifie que l'email correspond à un utilisateur existant et que cet utilisateur n'est pas déjà enrégistré comme employé, 
    // puis crée un nouvel enregistrement dans la table des employées. 
    // Si l'ajout est reussi, elle redirige vers la liste des employés avec un message de succès. En cas d'erreur, elle redirige avec les messages d'erreur appropriés.

    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricule' => ['required', 'string', 'max:50', 'unique:employes,matricule'],
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'exists:users,email'],
            'telephone' => ['required', 'string', 'max:50'],
            'poste' => ['required', 'string', 'max:255'],
            'departement' => ['required', 'string', 'max:255'],
            'date_naissance' => ['required', 'date'],
            'date_embauche' => ['required', 'date'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user) {
            return redirect()
                ->route('admin.employes.index')
                ->withErrors(['email' => 'Cet email ne correspond à aucun utilisateur existant.'])
                ->withInput();
        }

        $employeExistant = Employe::where('user_id', $user->id)->first();

        if ($employeExistant) {
            return redirect()
                ->route('admin.employes.index')
                ->withErrors(['email' => 'Cet utilisateur est déjà enregistré comme employé.'])
                ->withInput();
        }

        Employe::create([
            'user_id' => $user->id,
            'matricule' => $validated['matricule'],
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'poste' => $validated['poste'],
            'departement' => $validated['departement'],
            'date_naissance' => $validated['date_naissance'],
            'date_embauche' => $validated['date_embauche'],
        ]);

        return redirect()
            ->route('admin.employes.index')
            ->with('success', 'Employé ajouté avec succès.');
    }

    // Cette methode update est utilisée pour modifier les informations d'un employé existant.
    // Elle valide les données d'entrée et met à jour les informations de l'employé dans la base de données.
    // Si la mise à jour est réussie, elle redirige vers la liste des employés avec un message de succès. En cas d'erreur, elle redirige avec les messages d'erreur appropriés.


    public function update(Request $request, Employe $employe)
    {
        $validated = $request->validate([
            'matricule' => [
                'required',
                'string',
                'max:50',
                Rule::unique('employes', 'matricule')->ignore($employe->id),
            ],
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::exists('users', 'email'),
            ],
            'telephone' => ['required', 'string', 'max:50'],
            'poste' => ['required', 'string', 'max:255'],
            'departement' => ['required', 'string', 'max:255'],
            'date_naissance' => ['required', 'date'],
            'date_embauche' => ['required', 'date'],
        ]);

        $employe->update($validated);

        return redirect()
            ->route('admin.employes.index')
            ->with('success', 'Employé modifié avec succès.');
    }

    // Cette méthode destroy est utilisée pour supprimer un employé de la base de données.
    // Elle prend en paramètre l'employé à supprimer, effectue la suppression, puis redirige vers la liste des employés avec un message de succès.

    public function destroy(Employe $employe)
    {
        $employe->delete();

        return redirect()
            ->route('admin.employes.index')
            ->with('success', 'Employé supprimé avec succès.');
    }

    // Cette methode determineStatutEmploye est une fonction privée utilisée pour calculer le statut actuel d'un employé en focntion de ses demandes de congé  et de permission.
    // Elle vérifie d'abord si l'employé a une demande de congé approuvée qui couvre la date actuelle, puis vérifie s'il a une demande de permission approuvée pour la date actuelle.
    // En fonction de ces vérifications, elle retourne le statut approprié : "En congé", "En permission", ou "Actif".

    private function determinerStatutEmploye(Employe $employe): string
    {
        $today = Carbon::today();

        $demandeConge = Demande::with('conge')
            ->where('employe_id', $employe->id)
            ->where('type_demande', 'conge')
            ->whereIn('statut', ['approuve', 'approuver', 'valide'])
            ->get()
            ->first(function ($demande) use ($today) {
                return $demande->conge
                    && $demande->conge->date_debut
                    && $demande->conge->date_fin
                    && $today->between(
                        Carbon::parse($demande->conge->date_debut),
                        Carbon::parse($demande->conge->date_fin)
                    );
            });

        if ($demandeConge) {
            return 'En congé';
        }

        $demandePermission = Demande::with('permission')
            ->where('employe_id', $employe->id)
            ->where('type_demande', 'permission')
            ->whereIn('statut', ['approuve', 'approuver', 'valide'])
            ->get()
            ->first(function ($demande) use ($today) {
                return $demande->permission
                    && $demande->permission->date_permission
                    && Carbon::parse($demande->permission->date_permission)->isSameDay($today);
            });

        if ($demandePermission) {
            return 'En permission';
        }

        return 'Actif';
    }
}
