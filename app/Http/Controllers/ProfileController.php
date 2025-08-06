<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Tag; 
use Illuminate\Support\Str; 
use App\Models\Article; 

class ProfileController extends Controller
{
   
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => Auth::guard('admin')->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        $admin->fill($request->validated());

        if ($admin->isDirty('email')) {
            $admin->email_verified_at = null;
        }

        $admin->save();

        return Redirect::route('admin.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $admin = Auth::guard('admin')->user();

        Auth::guard('admin')->logout();

        $admin->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Store multiple tags from a comma-separated string.
     */
    public function storeMultipleTags(Request $request)
    {
     $request->validate([
            'tags' => 'required|string|max:1000',
        ]);

        $tagNames = explode(',', $request->tags);
        $createdTags = [];
        $duplicateTags = [];

        foreach ($tagNames as $tagName) {
        $tagName = trim($tagName);
            if (empty($tagName)) {
                continue;
            }

            // Utilisation de firstOrCreate pour simplifier la logique et gérer les doublons
            $tag = Tag::firstOrCreate(
                ['name' => $tagName], // Conditions pour trouver le tag
                [
                    'slug' => Str::slug($tagName),
                    'description' => 'Tag créé automatiquement'
                ] // Attributs à créer si le tag n'existe pas
            );

            if ($tag->wasRecentlyCreated) {
                $createdTags[] = $tagName;
            } else {
                $duplicateTags[] = $tagName;
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
    }

    public function debugMultipleTags(Request $request)
    {
        try {
            $data = [
                'all_data' => $request->all(),
                'tags_field' => $request->input('tags'),
                'method' => $request->method(),
            ];
        return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

   
    public function simpleMultipleTags(Request $request)
    {
        
        $tagsText = $request->input('tags', '');
     if (empty($tagsText)) {
            return redirect()->route('test.create.tag')->with('error', 'Veuillez entrer au moins un tag.');
        }

        // Séparer par virgules et nettoyer
        $tagNames = array_map('trim', explode(',', $tagsText));
        $tagNames = array_filter($tagNames);

        $created = 0;
        $existing = 0;
        $createdList = [];
        $existingList = [];

        foreach ($tagNames as $tagName) {
            if (strlen($tagName) < 2) continue; // Validation minimale de la longueur

            // Utilisation de firstOrCreate
            $tag = Tag::firstOrCreate(
                ['name' => $tagName],
                [
                    'slug' => Str::slug($tagName),
                    'description' => 'Tag créé automatiquement'
                ]
            );

            if ($tag->wasRecentlyCreated) {
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
    }

    
    public function testHome()
    {
        $articles = Article::with(['category', 'tags', 'admin'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        return view('public.home', compact('articles'));
    }
}