<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Visiteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisiteurController extends Controller
{
    /**
     * Affiche la liste des articles pour les visiteurs (page d'accueil)
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function viewArticles(Request $request)
    {
        $query = Article::with(['category', 'tags', 'admin'])
            ->where('status', 'published')
            ->whereNotNull('published_at');

        // Filtrer par catégorie si spécifiée
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filtrer par tag si spécifié
        if ($request->filled('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // Trier par date de publication (plus récent en premier)
        $articles = $query->orderBy('published_at', 'desc')->paginate(12);

        // Récupérer les catégories pour le menu
        $categories = Category::withCount(['articles' => function($query) {
            $query->where('status', 'published');
        }])->orderBy('name')->get();

        // Récupérer les tags utilisés avec le nombre d'articles publiés
        $popularTags = Tag::withCount(['articles' => function($query) {
            $query->where('status', 'published');
        }])
        ->having('articles_count', '>', 0)  // Seulement les tags utilisés
        ->orderBy('articles_count', 'desc')
        ->limit(15)
        ->get();

        // Récupérer les articles récents (au lieu des plus lus)
        $popularArticles = Article::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('public.articles.index', compact(
            'articles',
            'categories',
            'popularTags',
            'popularArticles'
        ));
    }

    /**
     * Affiche un article spécifique et enregistre la visite
     * 
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function readArticle($slug, Request $request)
    {
        // Récupérer l'article par son slug
        $article = Article::with(['category', 'tags', 'admin'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->firstOrFail();

        // Enregistrer la visite
        $this->recordVisit($article, $request);

        // Récupérer les articles similaires (même catégorie)
        $relatedArticles = Article::with(['category', 'admin'])
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(4)
            ->get();

        // Récupérer les articles précédent et suivant
        $previousArticle = Article::where('published_at', '<', $article->published_at)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->first();

        $nextArticle = Article::where('published_at', '>', $article->published_at)
            ->where('status', 'published')
            ->orderBy('published_at', 'asc')
            ->first();

        return view('public.articles.show', compact(
            'article', 
            'relatedArticles', 
            'previousArticle', 
            'nextArticle'
        ));
    }

    /**
     * Affiche la page "À propos"
     * 
     * @return \Illuminate\View\View
     */
    public function aboutPage()
    {
        // Récupérer quelques statistiques pour la page à propos
        $stats = [
            'total_articles' => Article::where('status', 'published')->count(),
            'total_categories' => Category::count(),
            'total_visits' => Visiteur::count(),
            'latest_article' => Article::where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->first()
        ];

        return view('public.about', compact('stats'));
    }

    /**
     * Effectue une recherche dans les articles
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $articles = collect();

        if (strlen($query) >= 3) {
            $articles = Article::with(['category', 'tags', 'admin'])
                ->where('status', 'published')
                ->where(function($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('content', 'LIKE', "%{$query}%")
                      ->orWhere('excerpt', 'LIKE', "%{$query}%");
                })
                ->orderBy('published_at', 'desc')
                ->paginate(10);
        }

        return view('public.search', compact('articles', 'query'));
    }

    /**
     * Filtre les articles par catégorie
     * 
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterByCategory($slug, Request $request)
    {
        // Récupérer la catégorie
        $category = Category::where('slug', $slug)->firstOrFail();

        // Récupérer les articles de cette catégorie
        $articles = Article::with(['category', 'tags', 'admin'])
            ->where('category_id', $category->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Récupérer les autres catégories
        $categories = Category::withCount(['articles' => function($query) {
            $query->where('status', 'published');
        }])->orderBy('name')->get();

        return view('public.categories.show', compact('category', 'articles', 'categories'));
    }

    /**
     * Filtre les articles par tag
     * 
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterByTag($slug, Request $request)
    {
        // Récupérer le tag
        $tag = Tag::where('slug', $slug)->firstOrFail();

        // Récupérer les articles avec ce tag
        $articles = $tag->articles()
            ->with(['category', 'admin'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Récupérer les tags populaires
        $popularTags = Tag::withCount(['articles' => function($query) {
            $query->where('status', 'published');
        }])
        ->orderBy('articles_count', 'desc')
        ->limit(20)
        ->get();

        return view('public.tags.show', compact('tag', 'articles', 'popularTags'));
    }

    /**
     * Récupère les articles les plus populaires
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popularArticles(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        $articles = Article::withCount('visiteurs')
            ->where('status', 'published')
            ->orderBy('visiteurs_count', 'desc')
            ->limit($limit)
            ->get();

        return response()->json($articles);
    }

    /**
     * Enregistre une visite d'article
     * 
     * @param \App\Models\Article $article
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    private function recordVisit(Article $article, Request $request)
    {
        // Éviter d'enregistrer plusieurs visites de la même session pour le même article
        $sessionId = $request->session()->getId();
        $ipAddress = $request->ip();

        $existingVisit = Visiteur::where('article_id', $article->id)
            ->where('session_id', $sessionId)
            ->where('ip_address', $ipAddress)
            ->whereDate('visit_date', today())
            ->first();

        if (!$existingVisit) {
            Visiteur::create([
                'article_id' => $article->id,
                'ip_address' => $ipAddress,
                'session_id' => $sessionId,
                'visit_date' => now(),
                'user_agent' => $request->userAgent()
            ]);
        }
    }
}