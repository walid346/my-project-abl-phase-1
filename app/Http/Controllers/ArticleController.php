<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\ArticleTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Admin;

class ArticleController extends Controller
{
    /**
     * Constructeur - Appliquer le middleware d'authentification admin
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Affiche la liste de tous les articles avec pagination et filtres
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Article::with(['category', 'tags', 'admin']);

        // Filtrer par statut si spécifié
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrer par catégorie si spécifiée
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Recherche par titre ou contenu
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        // Trier par date de création (plus récent en premier)
        $articles = $query->orderBy('created_at', 'desc')->paginate(15);

        // Récupérer les catégories pour le filtre
        $categories = Category::orderBy('name')->get();

        return view('admin.articles.index', compact('articles', 'categories'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel article
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.articles.create', compact('categories', 'tags'));
    }

    /**
     * Enregistre un nouvel article dans la base de données
     * 
     * @param \App\Http\Requests\StoreArticleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreArticleRequest $request)
    {
        $validated = $request->validated();

        // Générer un slug unique
        $validated['slug'] = $this->generateUniqueSlug($validated['title']);
        
        // Ajouter l'ID de l'admin connecté
        $validated['admin_id'] = Auth::guard('admin')->id();

        // Gérer l'upload d'image si présente
        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadImage($request->file('image'));
        }

        // Définir la date de publication si l'article est publié
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        // Créer l'article
        $article = Article::create($validated);

        // Assigner les tags si sélectionnés
        if ($request->filled('tags')) {
            $this->assignTags($article, $request->tags);
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article créé avec succès !');
    }

    /**
     * Affiche un article spécifique
     * 
     * @param \App\Models\Article $article
     * @return \Illuminate\View\View
     */
    public function show(Article $article)
    {
        $article->load(['category', 'tags', 'admin', 'visiteurs']);
        
        return view('admin.articles.show', compact('article'));
    }

    /**
     * Affiche le formulaire d'édition d'un article
     * 
     * @param \App\Models\Article $article
     * @return \Illuminate\View\View
     */
    public function edit(Article $article)
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $article->load('tags');

        return view('admin.articles.edit', compact('article', 'categories', 'tags'));
    }

    /**
     * Met à jour un article existant
     * 
     * @param \App\Http\Requests\UpdateArticleRequest $request
     * @param \App\Models\Article $article
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $validated = $request->validated();

        // Générer un nouveau slug si le titre a changé
        if ($validated['title'] !== $article->title) {
            $validated['slug'] = $this->generateUniqueSlug($validated['title'], $article->id);
        }

        // Gérer l'upload d'une nouvelle image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $validated['image'] = $this->uploadImage($request->file('image'));
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
        if ($request->has('tags')) {
            $this->assignTags($article, $request->tags ?? []);
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article mis à jour avec succès !');
    }

    /**
     * Supprime un article
     * 
     * @param \App\Models\Article $article
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Article $article)
    {
        // Supprimer l'image associée si elle existe
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        // Supprimer les relations avec les tags
        $article->tags()->detach();

        // Supprimer l'article
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article supprimé avec succès !');
    }

    /**
     * Publie un article (change le statut en publié)
     *
     * @param \App\Models\Article $article
     * @return \Illuminate\Http\RedirectResponse
     */
    public function publish(Article $article)
    {
        $article->update([
            'status' => 'published',
            'published_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Article validé et publié avec succès !');
    }

    /**
     * Assigne une catégorie à un article
     * 
     * @param \App\Models\Article $article
     * @param int $categoryId
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignCategory(Article $article, Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id'
        ]);

        $article->update(['category_id' => $request->category_id]);

        return response()->json([
            'success' => true,
            'message' => 'Catégorie assignée avec succès !'
        ]);
    }

    /**
     * Assigne des tags à un article
     * 
     * @param \App\Models\Article $article
     * @param array $tagIds
     * @return void
     */
    public function assignTags(Article $article, array $tagIds = [])
    {
        // Supprimer les anciennes relations
        ArticleTag::where('article_id', $article->id)->delete();

        // Créer les nouvelles relations
        foreach ($tagIds as $tagId) {
            ArticleTag::create([
                'article_id' => $article->id,
                'tag_id' => $tagId,
                'assigned_at' => now()
            ]);
        }
    }

    /**
     * Génère un slug unique pour l'article
     * 
     * @param string $title
     * @param int|null $excludeId
     * @return string
     */
    private function generateUniqueSlug($title, $excludeId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Article::where('slug', $slug);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Upload une image et retourne le chemin
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    private function uploadImage($file)
    {
        return $file->store('articles', 'public');
    }







//////






     






public function relations()
    {
        try {
            $article = Article::with(['category', 'tags', 'admin'])->first();
            if ($article) {
                return "Relations OK - Article: {$article->title}, Catégorie: " . ($article->category ? $article->category->name : 'Aucune');
            }
            return "Aucun article trouvé";
        } catch (\Exception $e) {
            return "Erreur Relations: " . $e->getMessage();
        }
    }

    public function controller()
    {
        try {
            $articles = Article::with(['category', 'tags', 'admin'])
                ->where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->paginate(6);

            return "Contrôleur OK - " . $articles->count() . " articles récupérés";
        } catch (\Exception $e) {
            return "Erreur Contrôleur: " . $e->getMessage();
        }
    }

    public function view()
    {
        try {
            $articles = Article::with(['category', 'tags', 'admin'])
                ->where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->paginate(6);

            return view('test-simple', compact('articles'));
        } catch (\Exception $e) {
            return "Erreur Vue: " . $e->getMessage();
        }
    }

    public function createArticle()
    {
       
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.articles.create', compact('categories', 'tags'));
    }
    public function storeArticle(Request $request)
    {
        

        // Validation
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
        $article = Article::create($validated);

        // Attacher les tags
        if (isset($validated['tags'])) {
            $article->tags()->attach($validated['tags']);
        }

        return redirect()->route('admin.articles.index')->with('success', 'Article créé avec succès !');
    }

    public function updateArticle(Request $request, Article $article)
    {
        $admin = Admin::where('email', 'admin@test.com')->first();
        Auth::guard('admin')->login($admin);

        // Validation
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
    }
    public function testArticle()
    {
        $article = Article::where('status', 'published')->first();

        if ($article) {
            return redirect()->route('public.article.show', $article->slug);
        } else {
            return "Aucun article publié trouvé. Créez d'abord un article avec le statut 'publié'.";
        }
    }
    }