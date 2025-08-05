<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Constructeur - Appliquer le middleware d'authentification admin
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Affiche la liste de toutes les catégories avec le nombre d'articles
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Category::withCount('articles');

        // Recherche par nom ou description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Trier par nom par défaut
        $categories = $query->orderBy('name')->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle catégorie
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Enregistre une nouvelle catégorie dans la base de données
     * 
     * @param \App\Http\Requests\StoreCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        // Générer un slug unique basé sur le nom
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);

        // Gérer l'upload d'image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        // Créer la catégorie
        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès !');
    }

    /**
     * Affiche une catégorie spécifique avec ses articles
     * 
     * @param \App\Models\Category $category
     * @return \Illuminate\View\View
     */
    public function show(Category $category)
    {
        // Charger les articles de cette catégorie avec pagination
        $articles = $category->articles()
            ->with(['admin', 'tags'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.categories.show', compact('category', 'articles'));
    }

    /**
     * Affiche le formulaire d'édition d'une catégorie
     * 
     * @param \App\Models\Category $category
     * @return \Illuminate\View\View
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Met à jour une catégorie existante
     * 
     * @param \App\Http\Requests\UpdateCategoryRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        // Générer un nouveau slug si le nom a changé
        if ($validated['name'] !== $category->name) {
            $validated['slug'] = $this->generateUniqueSlug($validated['name'], $category->id);
        }

        // Gérer l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        // Mettre à jour la catégorie
        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès !');
    }

    /**
     * Supprime une catégorie
     * 
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        // Vérifier s'il y a des articles associés
        if ($category->articles()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des articles. Veuillez d\'abord déplacer ou supprimer les articles associés.');
        }

        // Supprimer la catégorie
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès !');
    }

    /**
     * Valide une catégorie (méthode personnalisée pour validation)
     * 
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function activate(Category $category)
    {
        // Vérifier que la catégorie a un nom et une description
        $isValid = !empty($category->name) && !empty($category->description);

        if ($isValid) {
            return response()->json([
                'valid' => true,
                'message' => 'Catégorie valide !',
                'category' => $category
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Catégorie invalide : nom et description requis.',
            'errors' => [
                'name' => empty($category->name) ? 'Le nom est requis' : null,
                'description' => empty($category->description) ? 'La description est requise' : null
            ]
        ], 422);
    }

    /**
     * Récupère les statistiques d'une catégorie
     * 
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Category $category)
    {
        $stats = [
            'total_articles' => $category->articles()->count(),
            'published_articles' => $category->articles()->where('status', 'published')->count(),
            'draft_articles' => $category->articles()->where('status', 'draft')->count(),
            'latest_article' => $category->articles()->latest()->first(),
            'oldest_article' => $category->articles()->oldest()->first()
        ];

        return response()->json($stats);
    }

    /**
     * Génère un slug unique pour la catégorie
     * 
     * @param string $name
     * @param int|null $excludeId
     * @return string
     */
    private function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Category::where('slug', $slug);
            
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
}
