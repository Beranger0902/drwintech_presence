<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login'); // ton fichier blade
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Identifiants incorrects']);
        }

        $user = Auth::user();

        if ($user->role !== 'administrateur') {
            Auth::logout();
            return back()->withErrors(['email' => 'Accès refusé']);
        }

        // 🔐 Génération OTP
        $otp = rand(100000, 999999);

        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5)
        ]);

        // 📧 Envoi email
        Mail::raw("Votre code OTP est : $otp", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Code de vérification');
        });

        return redirect()->route('admin.verify.form');
    }

    public function showVerifyForm()
    {
        return view('admin.auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $user = Auth::user();

        if (
            $user->otp_code == $request->otp &&
            now()->lessThanOrEqualTo($user->otp_expires_at)
        ) {

            // Nettoyage
            $user->update([
                'otp_code' => null,
                'otp_expires_at' => null
            ]);

            return redirect('/admin/dashboard');
        }


        return back()->withErrors(['otp' => 'Code invalide ou expiré']);
    }

     public function resendOtp()
    {
        $user = Auth::user();

        $otp = rand(100000, 999999);

        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5)
        ]);

        Mail::raw("Votre nouveau code OTP est : $otp", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Nouveau code OTP');
        });

        return back()->with('success', 'Code renvoyé');
    }
}
