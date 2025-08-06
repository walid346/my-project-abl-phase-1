<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
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









/////



public function updateProfile(Request $request)
    {
        $admin = Admin::where('email', 'admin@test.com')->first();
        Auth::guard('admin')->login($admin);

        // Validation
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
        ]);

        // Mettre à jour l'admin
        $admin->update($validated);

        return redirect()->route('admin.profile.edit')->with('status', 'profile-updated');
    }
    public function storeMultipleTags(Request $request)
    {
        $admin = Admin::where('email', 'admin@test.com')->first();
        Auth::guard('admin')->login($admin);

        // Validation
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
        // Connexion automatique admin
        $admin = Admin::where('email', 'admin@test.com')->first();
        Auth::guard('admin')->login($admin);

        // Récupérer le texte des tags
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
            if (strlen($tagName) < 2) continue;

            $exists = Tag::where('name', $tagName)->exists();

            if (!$exists) {
                Tag::create([
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