<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ─── Show login form ────────────────────────────────────
    public function showLogin()
    {
        return view('auth.login');
    }

    // ─── Handle login ───────────────────────────────────────
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && !$user->is_active) {
            return back()->withErrors(['email' => 'Votre compte est désactivé. Contactez l\'administrateur.']);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended($this->redirectBasedOnRole());
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.'])->onlyInput('email');
    }

    // ─── Show register form ──────────────────────────────────
    public function showRegister()
    {
        return view('auth.register');
    }

    // ─── Handle registration (patients only) ────────────────
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users'],
            'phone'    => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'patient',
        ]);

        Patient::create(['user_id' => $user->id]);

        Auth::login($user);

        return redirect()->route('patient.dashboard')
                         ->with('success', 'Bienvenue ! Votre compte a été créé avec succès.');
    }

    // ─── Logout ─────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Déconnexion réussie.');
    }

    // ─── Show forgot password form ───────────────────────────
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // ─── Redirect based on role ──────────────────────────────
    private function redirectBasedOnRole(): string
    {
        return match (Auth::user()->role) {
            'admin'     => route('admin.dashboard'),
            'doctor'    => route('doctor.dashboard'),
            'secretary' => route('secretary.dashboard'),
            'patient'   => route('patient.dashboard'),
            default     => '/',
        };
    }
}
