<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Visiteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    
    public function home(Request $request)
    {
        try {
            $articles = Article::with(['category', 'tags', 'admin'])
                ->where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->paginate(6);

            return view('public.home', compact('articles'));
        } catch (\Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    
    public function showArticle($slug)
    {
        $article = Article::with(['category', 'tags', 'admin'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Get previous and next articles (only if published_at is not null)
        $previousArticle = null;
        $nextArticle = null;

        if ($article->published_at) {
            $previousArticle = Article::where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<', $article->published_at)
                ->orderBy('published_at', 'desc')
                ->first();

            $nextArticle = Article::where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '>', $article->published_at)
                ->orderBy('published_at', 'asc')
                ->first();
        }

        return view('public.article', compact('article', 'previousArticle', 'nextArticle'));
    }

    
    public function about()
    {
        return view('public.about');
    }

    public function filterByCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $articles = Article::with(['category', 'tags', 'admin'])
            ->where('category_id', $category->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        return view('public.home', compact('articles'))
            ->with('category', $category);
    }

    public function filterByTag($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        
        $articles = Article::with(['category', 'tags', 'admin'])
            ->whereHas('tags', function ($q) use ($tag) {
                $q->where('tags.id', $tag->id);
            })
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        return view('public.home', compact('articles'))
            ->with('tag', $tag);
    }

    
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return redirect()->route('public.home');
        }

        $articles = Article::with(['category', 'tags', 'admin'])
            ->where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        return view('public.home', compact('articles'))
            ->with('searchQuery', $query);
    }

    
    private function trackVisitor(Request $request)
    {
        try {
            Visiteur::create([
                'ip_address' => $request->ip(),
                'session_id' => session()->getId(),
                'visit_date' => now(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silently fail if visitor tracking fails
            \Log::warning('Failed to track visitor: ' . $e->getMessage());
        }
    }
}
