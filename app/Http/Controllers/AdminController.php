<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Visiteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateAdminRequest;

class AdminController extends Controller
{
    /**
     * Constructeur - Appliquer le middleware d'authentification admin
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Affiche le tableau de bord administrateur avec les statistiques
     * 
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Récupérer les statistiques générales
        $stats = [
            'total_articles' => Article::count(),
            'published_articles' => Article::where('status', 'published')->count(),
            'draft_articles' => Article::where('status', 'draft')->count(),
            'total_categories' => Category::count(),
            'total_tags' => Tag::count(),
            'total_visitors' => Visiteur::count(),
            'today_visitors' => Visiteur::whereDate('visit_date', today())->count(),
        ];

        // Récupérer les articles récents
        $recent_articles = Article::with(['category', 'admin'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Récupérer les articles les plus vus (basé sur les visiteurs)
        $popular_articles = Article::withCount('visiteurs')
            ->orderBy('visiteurs_count', 'desc')
            ->limit(5)
            ->get();

        // Récupérer les catégories avec le nombre d'articles
        $categories_stats = Category::withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->get();

        // Récupérer les visiteurs récents
        $recent_visitors = Visiteur::with('article')
            ->orderBy('visit_date', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recent_articles',
            'popular_articles',
            'categories_stats',
            'recent_visitors'
        ));
    }

    /**
     * Affiche le formulaire de modification du profil admin
     * 
     * @return \Illuminate\View\View
     */
    public function editProfile()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Met à jour le profil de l'administrateur
     * 
     * @param \App\Http\Requests\UpdateAdminRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(UpdateAdminRequest $request)
    {
        $admin = Auth::guard('admin')->user();
        $validated = $request->validated();

        // Mettre à jour les informations de base
        $admin->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
        ]);

        // Mettre à jour le mot de passe si fourni
        if (!empty($validated['password'])) {
            $admin->update([
                'password' => Hash::make($validated['password'])
            ]);
        }

        // Enregistrer l'activité
        $this->logActivity('Profil mis à jour');

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Met à jour uniquement le nom d'utilisateur (requête AJAX)
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:admins,username,' . Auth::guard('admin')->id(),
        ]);

        $admin = Auth::guard('admin')->user();
        $old_username = $admin->username;
        
        $admin->update([
            'username' => $request->username
        ]);

        // Enregistrer l'activité
        $this->logActivity("Nom d'utilisateur changé de '{$old_username}' à '{$request->username}'");

        return response()->json([
            'success' => true,
            'message' => 'Nom d\'utilisateur mis à jour avec succès !',
            'new_username' => $request->username
        ]);
    }

    /**
     * Affiche les paramètres du système
     * 
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Enregistre l'activité de l'administrateur
     * 
     * @param string $activity
     * @return void
     */
    private function logActivity($activity)
    {
        \Log::info($activity, [
            'admin_id' => Auth::guard('admin')->id(),
            'admin_username' => Auth::guard('admin')->user()->username,
            'ip_address' => request()->ip(),
            'timestamp' => now()
        ]);
    }






/////////




    public function adminDirect()
    {
        $admin = Admin::where('email', 'admin@test.com')->first();
        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.dashboard');
    }
    }