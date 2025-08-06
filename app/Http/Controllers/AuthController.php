<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion
     * 
      
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // Si l'utilisateur est déjà connecté, rediriger vers le dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Traite la tentative de connexion
     * 
     * @param \App\Http\Requests\LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request)
    {
        // Récupérer les credentials validés
        $credentials = $request->validated();

        // Tentative d'authentification avec le guard 'admin'
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            // Régénérer la session pour éviter la fixation de session
            $request->session()->regenerate();

            // Enregistrer l'activité de connexion
            $this->logActivity('Connexion réussie');

            // Rediriger vers le dashboard avec message de succès
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Connexion réussie ! Bienvenue dans votre espace d\'administration.');
        }

        // En cas d'échec, retourner avec erreur
        return back()
            ->withErrors(['email' => 'Les identifiants fournis ne correspondent à aucun compte.'])
            ->withInput($request->except('password'));
    }

    /**
     * Déconnecte l'utilisateur admin
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Enregistrer l'activité de déconnexion avant de se déconnecter
        $this->logActivity('Déconnexion');

        // Déconnexion du guard admin
        Auth::guard('admin')->logout();

        // Invalider la session
        $request->session()->invalidate();

        // Régénérer le token CSRF
        $request->session()->regenerateToken();

        // Rediriger vers la page de connexion
        return redirect()->route('login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Vérifie si l'utilisateur est authentifié (pour les requêtes AJAX)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate()
    {
        if (Auth::guard('admin')->check()) {
            return response()->json([
                'authenticated' => true,
                'user' => Auth::guard('admin')->user(),
                'message' => 'Utilisateur authentifié'
            ]);
        }

        return response()->json([
            'authenticated' => false,
            'message' => 'Utilisateur non authentifié'
        ], 401);
    }

    /**
     * Enregistre l'activité de l'utilisateur (helper method)
     * 
     * @param string $activity
     * @return void
     */
    private function logActivity($activity)
    {
        if (Auth::guard('admin')->check()) {
            // Ici vous pouvez enregistrer l'activité dans une table de logs
            \Log::info($activity, [
                'admin_id' => Auth::guard('admin')->id(),
                'admin_email' => Auth::guard('admin')->user()->email,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        }
    } 
public function testAuth()
    {
        
        return redirect()->route('admin.dashboard')->with('success', 'Connexion automatique réussie !');
    }
    }