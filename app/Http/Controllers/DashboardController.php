<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): RedirectResponse|View
    {
        $user = $request->user();

        return match ($user->role) {
            User::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            User::ROLE_AGENT => redirect()->route('agent.dashboard'),
            User::ROLE_EMPLOYE => redirect()->route('employe.dashboard'),
            default => abort(403, 'Rôle utilisateur invalide.'),
        };
    }
}
