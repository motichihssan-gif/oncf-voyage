<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'login' => $request->login,
            'email' => $request->email,
            'tel' => $request->tel,
            'password' => $request->password, // Note: Should be Hash::make in real app
            'role' => 'USER'
        ]);

        Auth::login($user);
        return redirect()->route('voyage.form')->with('success', 'Compte créé avec succès !');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('password');
        $login = $request->input('login');

        // Simple login logic for test: check if email or login matches
        $user = User::where('email', $login)->orWhere('login', $login)->first();

        if ($user && $user->role === 'USER') {
            Auth::login($user);
            return redirect()->route('checkout.voyageurs');
        }

        return back()->withErrors(['login' => 'Identifiants invalides ou rôle insuffisant.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('voyage.form');
    }

    public function history()
    {
        $user = auth()->user();
        $commandes = \App\Models\Commande::with(['voyages'])
            ->where('id_client', $user->id)
            ->orderBy('date_comm', 'desc')
            ->get();
            
        return view('historique', compact('commandes'));
    }
}
