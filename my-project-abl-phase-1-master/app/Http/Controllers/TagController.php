<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\ArticleTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;

class TagController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Affiche la liste de tous les tags avec le nombre d'articles associés
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Tag::withCount('articles');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $sortBy = $request->get('sort', 'name');
        if ($sortBy === 'popularity') {
            $tags = $query->orderBy('articles_count', 'desc')->paginate(20);
        } else {
            $tags = $query->orderBy('name')->paginate(20);
        }

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau tag
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Enregistre un nouveau tag dans la base de données
     * 
     * @param \App\Http\Requests\StoreTagRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTagRequest $request)
    {
        $validated = $request->validated();

        // Générer un slug unique basé sur le nom
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);

        // Créer le tag
        Tag::create($validated);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag créé avec succès !');
    }

    /**
     * Affiche un tag spécifique avec ses articles associés
     * 
     * @param \App\Models\Tag $tag
     * @return \Illuminate\View\View
     */
    public function show(Tag $tag)
    {
        // Charger les articles associés à ce tag avec pagination
        $articles = $tag->articles()
            ->with(['admin', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.tags.show', compact('tag', 'articles'));
    }

    /**
     * Affiche le formulaire d'édition d'un tag
     * 
     * @param \App\Models\Tag $tag
     * @return \Illuminate\View\View
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Met à jour un tag existant
     * 
     * @param \App\Http\Requests\UpdateTagRequest $request
     * @param \App\Models\Tag $tag
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $validated = $request->validated();

        // Générer un nouveau slug si le nom a changé
        if ($validated['name'] !== $tag->name) {
            $validated['slug'] = $this->generateUniqueSlug($validated['name'], $tag->id);
        }

        // Mettre à jour le tag
        $tag->update($validated);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag mis à jour avec succès !');
    }

    /**
     * Supprime un tag
     * 
     * @param \App\Models\Tag $tag
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Tag $tag)
    {
        // Supprimer toutes les relations avec les articles
        ArticleTag::where('tag_id', $tag->id)->delete();

        // Supprimer le tag
        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag supprimé avec succès !');
    }

    /**
     * Vérifie la validité d'un tag (méthode personnalisée pour validation)
     *
     * @param \App\Models\Tag $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Tag $tag)
    {
        // Vérifier que le tag a un nom valide
        $isValid = !empty($tag->name) && strlen($tag->name) >= 2;

        if ($isValid) {
            return response()->json([
                'valid' => true,
                'message' => 'Tag valide !',
                'tag' => $tag,
                'articles_count' => $tag->articles()->count()
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Tag invalide : le nom doit contenir au moins 2 caractères.',
            'errors' => [
                'name' => 'Le nom doit contenir au moins 2 caractères'
            ]
        ], 422);
    }

    /**
     * Récupère les tags les plus populaires
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popular(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        $popularTags = Tag::withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'popular_tags' => $popularTags,
            'total_tags' => Tag::count()
        ]);
    }

    /**
     * Recherche de tags par nom (pour l'autocomplétion)
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $tags = Tag::where('name', 'LIKE', "%{$query}%")
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'slug']);

        return response()->json($tags);
    }

    /**
     * Génère un slug unique pour le tag
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
            $query = Tag::where('slug', $slug);
            
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
     * Crée plusieurs tags à partir d'une chaîne séparée par des virgules
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeMultiple(Request $request)
    {
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
            $existingTag = Tag::where('name', $tagName)->first();

            if ($existingTag) {
                $duplicateTags[] = $tagName;
            } else {
                // Créer le nouveau tag
                $tag = Tag::create([
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

        return redirect()->route('test.create.tag')
            ->with('success', $message ?: 'Aucun tag valide trouvé.');
    }
}