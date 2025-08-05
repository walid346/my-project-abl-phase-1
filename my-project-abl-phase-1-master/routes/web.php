<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage and public pages
Route::get('/test', function() {
    return 'Test OK - Laravel fonctionne !';
});

Route::get('/test-db', function() {
    try {
        $count = \App\Models\Article::count();
        return "Base de données OK - {$count} articles trouvés";
    } catch (\Exception $e) {
        return "Erreur DB: " . $e->getMessage();
    }
});

Route::get('/test-relations', function() {
    try {
        $article = \App\Models\Article::with(['category', 'tags', 'admin'])->first();
        if ($article) {
            return "Relations OK - Article: {$article->title}, Catégorie: " . ($article->category ? $article->category->name : 'Aucune');
        }
        return "Aucun article trouvé";
    } catch (\Exception $e) {
        return "Erreur Relations: " . $e->getMessage();
    }
});

Route::get('/test-controller', function() {
    try {
        $articles = \App\Models\Article::with(['category', 'tags', 'admin'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        return "Contrôleur OK - " . $articles->count() . " articles récupérés";
    } catch (\Exception $e) {
        return "Erreur Contrôleur: " . $e->getMessage();
    }
});

Route::get('/test-view', function() {
    try {
        $articles = \App\Models\Article::with(['category', 'tags', 'admin'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        return view('test-simple', compact('articles'));
    } catch (\Exception $e) {
        return "Erreur Vue: " . $e->getMessage();
    }
});

Route::get('/', [PublicController::class, 'home'])->name('public.home');
Route::get('/about', [PublicController::class, 'about'])->name('public.about');
Route::get('/search', [PublicController::class, 'search'])->name('public.search');

// Article routes
Route::get('/article/{slug}', [PublicController::class, 'showArticle'])->name('public.article.show');

// Category and tag filtering
Route::get('/category/{slug}', [PublicController::class, 'filterByCategory'])->name('public.category.filter');
Route::get('/tag/{slug}', [PublicController::class, 'filterByTag'])->name('public.tag.filter');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Routes d'authentification personnalisées
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.show');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:admin');

// Auth check for AJAX
Route::get('/auth/check', [AuthController::class, 'authenticate'])->name('auth.check');

// Route de test temporaire
Route::get('/test-auth', function() {
    $admin = App\Models\Admin::where('email', 'admin@test.com')->first();
    Auth::guard('admin')->login($admin);

    return redirect()->route('admin.dashboard')->with('success', 'Connexion automatique réussie !');
})->name('test.auth');

// Route d'accès direct au dashboard (sans message de connexion)
Route::get('/admin-direct', function() {
    $admin = App\Models\Admin::where('email', 'admin@test.com')->first();
    Auth::guard('admin')->login($admin);

    return redirect()->route('admin.dashboard');
})->name('admin.direct');

// Route de test pour formulaire de création d'article
Route::get('/test-create-article', function() {
    $admin = App\Models\Admin::where('email', 'admin@test.com')->first();
    Auth::guard('admin')->login($admin);

    $categories = App\Models\Category::all();
    $tags = App\Models\Tag::all();

    return view('admin.articles.create', compact('categories', 'tags'));
})->name('test.create.article');

// Route de test pour formulaire de création de catégorie
Route::get('/test-create-category', function() {
    $admin = App\Models\Admin::where('email', 'admin@test.com')->first();
    Auth::guard('admin')->login($admin);

    return view('admin.categories.create');
})->name('test.create.category');

// Route de test pour formulaire de création de tag
Route::get('/test-create-tag', function() {
    $admin = App\Models\Admin::where('email', 'admin@test.com')->first();
    Auth::guard('admin')->login($admin);

    return view('admin.tags.create');
})->name('test.create.tag');

// Routes de test pour traitement des formulaires
Route::post('/test-store-category', function(\Illuminate\Http\Request $request) {
    $admin = App\Models\Admin::where('email', 'admin@test.com')->first();
    Auth::guard('admin')->login($admin);

    // Validation simple
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:categories',
        'description' => 'nullable|string'
    ]);

    // Générer un slug
    $validated['slug'] = Str::slug($validated['name']);

    // Créer la catégorie
    App\Models\Category::create($validated);

    return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée avec succès !');
})->name('test.store.category');

Route::post('/test-store-tag', function(\Illuminate\Http\Request $request) {
    $admin = App\Models\Admin::where('email', 'admin@test.com')->first();
    Auth::guard('admin')->login($admin);

    // Validation simple
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:tags'
    ]);

    // Générer un slug
    $validated['slug'] = Str::slug($validated['name']);

    // Créer le tag
    App\Models\Tag::create($validated);

    return redirect()->route('admin.tags.index')->with('success', 'Tag "' . $validated['name'] . '" créé avec succès !');
})->name('test.store.tag');

Route::post('/test-store-article', function(\Illuminate\Http\Request $request) {
    $admin = App\Models\Admin::where('email', 'admin@test.com')->first();
    Auth::guard('admin')->login($admin);

    // Validation simple
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'excerpt' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'tags' => 'nullable|array',
        'tags.*' => 'exists:tags,id',
        'status' => 'required|in:draft,published',
        'image' => 'nullable|image|max:2048'
    ]);

    // Générer un slug
    $validated['slug'] = Str::slug($validated['title']);
    $validated['admin_id'] = Auth::guard('admin')->id();

    // Gérer l'upload d'image si présente
    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('articles', 'public');
    }

    // Créer l'article
    $article = App\Models\Article::create($validated);

    // Attacher les tags
    if (isset($validated['tags'])) {
        $article->tags()->attach($validated['tags']);
    }

    return redirect()->route('admin.articles.index')->with('success', 'Article créé avec succès !');
})->name('test.store.article');

// Route de test pour la mise à jour d'article
Route::put('/test-update-article/{article}', function(\Illuminate\Http\Request $request, App\Models\Article $article) {
    $admin = App\Models\Admin::where('email', 'admin@test.com')->first();
    Auth::guard('admin')->login($admin);

    // Validation simple
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'excerpt' => 'nullable|string',
        'category_id' => 'nullable|exists:categories,id',
        'tags' => 'nullable|array',
        'tags.*' => 'exists:tags,id',
        'status' => 'required|in:draft,published',
        'image' => 'nullable|image|max:2048'
    ]);

    // Générer un nouveau slug si le titre a changé
    if ($validated['title'] !== $article->title) {
        $validated['slug'] = Str::slug($validated['title']);
    }

    // Gérer l'upload d'image si présente
    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('articles', 'public');
    }

    // Gérer le changement de statut
    if ($validated['status'] === 'published' && $article->status !== 'published') {
        $validated['published_at'] = now();
    } elseif ($validated['status'] === 'draft') {
        $validated['published_at'] = null;
    }

    // Mettre à jour l'article
    $article->update($validated);

    // Mettre à jour les tags
    if (isset($validated['tags'])) {
        $article->tags()->sync($validated['tags']);
    }

    return redirect()->route('admin.articles.index')->with('success', 'Article "' . $validated['title'] . '" mis à jour avec succès !');
})->name('test.update.article');

// Route de test pour la mise à jour du profil
Route::patch('/test-update-profile', function(\Illuminate\Http\Request $request) {
    $admin = App\Models\Admin::where('email', 'admin@test.com')->first();
    Auth::guard('admin')->login($admin);

    // Validation simple
    $validated = $request->validate([
        'username' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
    ]);

    // Mettre à jour l'admin
    $admin->update($validated);

    return redirect()->route('admin.profile.edit')->with('status', 'profile-updated');
})->name('test.update.profile');

// Route de test pour la création multiple de tags
Route::post('/test-store-multiple-tags', function(\Illuminate\Http\Request $request) {
    $admin = App\Models\Admin::where('email', 'admin@test.com')->first();
    Auth::guard('admin')->login($admin);

    // Validation
    $request->validate([
        'tags' => 'required|string|max:1000',
    ]);

    $tagNames = explode(',', $request->tags);
    $createdTags = [];
    $duplicateTags = [];

    foreach ($tagNames as $tagName) {
        $tagName = trim($tagName); // Supprimer les espaces

        if (empty($tagName)) {
            continue; // Ignorer les tags vides
        }

        // Vérifier si le tag existe déjà
        $existingTag = App\Models\Tag::where('name', $tagName)->first();

        if ($existingTag) {
            $duplicateTags[] = $tagName;
        } else {
            // Créer le nouveau tag
            $tag = App\Models\Tag::create([
                'name' => $tagName,
                'slug' => Str::slug($tagName),
                'description' => 'Tag créé automatiquement'
            ]);
            $createdTags[] = $tagName;
        }
    }

    // Préparer le message de retour
    $message = '';
    if (count($createdTags) > 0) {
        $message .= count($createdTags) . ' tag(s) créé(s) : ' . implode(', ', $createdTags);
    }
    if (count($duplicateTags) > 0) {
        if ($message) $message .= ' | ';
        $message .= count($duplicateTags) . ' tag(s) déjà existant(s) : ' . implode(', ', $duplicateTags);
    }

    return redirect()->route('admin.tags.index')
        ->with('success', $message ?: 'Aucun tag valide trouvé.');
})->name('test.store.multiple.tags');

// Route de debug pour tester la création multiple
Route::post('/debug-multiple-tags', function(\Illuminate\Http\Request $request) {
    try {
        // Debug des données reçues
        $data = [
            'all_data' => $request->all(),
            'tags_field' => $request->input('tags'),
            'method' => $request->method(),
        ];

        return response()->json($data);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('debug.multiple.tags');

// Version simple et sûre pour la création multiple
Route::post('/simple-multiple-tags', function(\Illuminate\Http\Request $request) {
    // Connexion automatique admin
    $admin = App\Models\Admin::where('email', 'admin@test.com')->first();
    Auth::guard('admin')->login($admin);

    // Récupérer le texte des tags
    $tagsText = $request->input('tags', '');

    if (empty($tagsText)) {
        return redirect()->route('test.create.tag')->with('error', 'Veuillez entrer au moins un tag.');
    }

    // Séparer par virgules et nettoyer
    $tagNames = array_map('trim', explode(',', $tagsText));
    $tagNames = array_filter($tagNames); // Supprimer les éléments vides

    $created = 0;
    $existing = 0;
    $createdList = [];
    $existingList = [];

    foreach ($tagNames as $tagName) {
        if (strlen($tagName) < 2) continue; // Ignorer les tags trop courts

        // Vérifier si existe
        $exists = App\Models\Tag::where('name', $tagName)->exists();

        if (!$exists) {
            App\Models\Tag::create([
                'name' => $tagName,
                'slug' => Str::slug($tagName),
                'description' => 'Tag créé automatiquement'
            ]);
            $created++;
            $createdList[] = $tagName;
        } else {
            $existing++;
            $existingList[] = $tagName;
        }
    }

    $message = "Résultat : {$created} tag(s) créé(s)";
    if ($existing > 0) {
        $message .= ", {$existing} déjà existant(s)";
    }

    return redirect()->route('admin.tags.index')->with('success', $message);
})->name('simple.multiple.tags');

// Route de test pour vérifier qu'un article existe et fonctionne
Route::get('/test-article', function() {
    $article = App\Models\Article::where('status', 'published')->first();

    if ($article) {
        return redirect()->route('public.article.show', $article->slug);
    } else {
        return "Aucun article publié trouvé. Créez d'abord un article avec le statut 'publié'.";
    }
})->name('test.article');

// Route de test pour forcer l'affichage de la page d'accueil
Route::get('/test-home', function() {
    $articles = App\Models\Article::with(['category', 'tags', 'admin'])
        ->where('status', 'published')
        ->orderBy('published_at', 'desc')
        ->paginate(6);

    return view('public.home', compact('articles'));
})->name('test.home');

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('/analytics/export', [DashboardController::class, 'exportAnalytics'])->name('analytics.export');

    // Articles management
    Route::resource('articles', ArticleController::class);

    // Categories management
    Route::resource('categories', CategoryController::class);

    // Tags management
    Route::resource('tags', TagController::class);
    Route::post('/tags/store-multiple', [TagController::class, 'storeMultiple'])->name('tags.store-multiple');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});