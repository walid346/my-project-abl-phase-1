# Guide de Traduction / Translation Guide

## Vue d'ensemble / Overview

Votre application Laravel supporte maintenant le système de traduction multilingue. Par défaut, l'application est configurée en **anglais** avec le français comme langue de secours.

Your Laravel application now supports multilingual translation system. By default, the application is configured in **English** with French as fallback language.

## Structure des fichiers / File Structure

```
lang/
├── en/
│   ├── auth.php          # Authentification (Laravel par défaut)
│   ├── pagination.php    # Pagination (Laravel par défaut)
│   ├── passwords.php     # Mots de passe (Laravel par défaut)
│   ├── validation.php    # Validation (Laravel par défaut)
│   └── public.php        # Interface publique (personnalisé)
└── fr/
    └── public.php        # Interface publique en français
```

## Configuration

### Changer la langue par défaut / Change Default Language

1. **Via le fichier .env :**
```env
APP_LOCALE=en          # ou 'fr' pour français
APP_FALLBACK_LOCALE=en # langue de secours
```

2. **Via le fichier config/app.php :**
```php
'locale' => env('APP_LOCALE', 'en'),
'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
```

## Utilisation dans les vues / Usage in Views

### Syntaxe de base / Basic Syntax
```php
{{ __('public.home') }}           # Affiche "Home" en anglais, "Accueil" en français
{{ __('public.about') }}          # Affiche "About" en anglais, "À propos" en français
```

### Dans les sections Blade / In Blade Sections
```php
@section('title', __('public.home_meta_title'))
@section('description', __('public.home_meta_description'))
```

## Clés de traduction disponibles / Available Translation Keys

### Navigation
- `public.home` - Accueil / Home
- `public.about` - À propos / About
- `public.administration` - Administration
- `public.search_placeholder` - Placeholder de recherche

### Page d'accueil / Home Page
- `public.welcome_to` - Bienvenue sur / Welcome to
- `public.latest_articles` - Derniers articles / Latest Articles
- `public.explore_articles` - Découvrir les articles / Explore Articles

### Statistiques / Statistics
- `public.articles` - Articles
- `public.readers` - Lecteurs / Readers
- `public.categories` - Catégories / Categories
- `public.since` - Depuis / Since

### Page À propos / About Page
- `public.about_me` - À propos de moi / About Me
- `public.contact_me` - Me contacter / Contact Me

## Ajouter de nouvelles traductions / Adding New Translations

1. **Ajoutez la clé dans `lang/en/public.php` :**
```php
'new_key' => 'English text',
```

2. **Ajoutez la traduction française dans `lang/fr/public.php` :**
```php
'new_key' => 'Texte français',
```

3. **Utilisez dans vos vues :**
```php
{{ __('public.new_key') }}
```

## Changer de langue dynamiquement / Dynamic Language Switching

Pour permettre aux utilisateurs de changer de langue, vous pouvez créer une route :

```php
// Dans routes/web.php
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'fr'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');
```

Et dans un middleware :
```php
// Dans app/Http/Middleware/SetLocale.php
public function handle($request, Closure $next)
{
    if (session('locale')) {
        app()->setLocale(session('locale'));
    }
    return $next($request);
}
```

## État actuel / Current Status

✅ **Traduit / Translated :**
- Layout principal / Main layout
- Page d'accueil / Home page
- Page À propos / About page
- Navigation / Navigation
- Footer

❌ **À traduire / To Translate :**
- Pages d'administration / Admin pages
- Pages d'authentification / Auth pages
- Messages d'erreur / Error messages
- Formulaires / Forms

## Commandes utiles / Useful Commands

```bash
# Vider le cache des traductions
php artisan cache:clear

# Publier les fichiers de langue Laravel
php artisan lang:publish
```
