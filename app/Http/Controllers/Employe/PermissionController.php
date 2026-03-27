<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
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

    public function store(Request $request)
    {
        $employe = $request->user()->employe;

        $validated = $request->validate([
            'date_permission' => ['required', 'date'],
            'heure_debut' => ['required'],
            'heure_fin' => ['required', 'after:heure_debut'],
            'observation' => ['nullable'],
        ]);

        DB::beginTransaction();

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

        return redirect()->back()->with('success', 'Permission envoyée');
    }
}
