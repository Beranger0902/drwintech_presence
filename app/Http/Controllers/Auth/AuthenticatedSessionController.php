<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
        {
            return view('auth.login');
        }

        /**
         * Handle an incoming authentication request.
         */
        public function store(LoginRequest $request): RedirectResponse
        {
            if (! Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors([
                'email' => 'Identifiants incorrects'
            ]);
        }

        $user = Auth::user();

        // 🚫 BLOQUER ADMIN
        if ($user->role === 'administrateur') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Utilisez la page admin pour vous connecter.'
            ]);
        }

        // ✅ Régénérer session
        $request->session()->regenerate();

        // ✅ Redirection selon rôle
        if ($user->role === 'employe') {
            return redirect()->route('employe.dashboard');
        }

        if ($user->role === 'agent_accueil') {
            return redirect()->route('agent.dashboard');
        }

        // ⚠️ sécurité fallback obligatoire
        return redirect('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
