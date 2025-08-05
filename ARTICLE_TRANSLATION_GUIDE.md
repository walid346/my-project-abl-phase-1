# Guide de Traduction des Articles

## Méthodes pour traduire vos articles existants

### 1. Via l'interface d'administration (Recommandée)

1. **Accédez à l'admin** : http://127.0.0.1:8000/admin/articles
2. **Identifiez les articles en français** dans la liste
3. **Cliquez sur "Modifier"** pour chaque article
4. **Traduisez** :
   - Le titre
   - Le contenu
   - L'extrait (si présent)
5. **Sauvegardez** les modifications

### 2. Via la commande Artisan (Pour traduction en masse)

J'ai créé une commande personnalisée pour vous aider :

```bash
# Voir ce qui serait traduit (mode test)
php artisan articles:translate --dry-run

# Appliquer les traductions
php artisan articles:translate
```

### 3. Identifier vos articles à traduire

Pour voir tous vos articles actuels :

```bash
php artisan tinker
```

Puis dans tinker :
```php
// Voir tous les articles
App\Models\Article::all(['id', 'title'])->each(function($article) {
    echo "ID: {$article->id} - Title: {$article->title}\n";
});

// Voir les articles récents (probablement les vôtres)
App\Models\Article::latest()->take(10)->get(['id', 'title', 'created_at']);
```

### 4. Modifier le script de traduction

Éditez le fichier `app/Console/Commands/TranslateArticles.php` et ajoutez vos traductions dans le tableau `$translations` :

```php
$translations = [
    'Votre titre français' => [
        'title' => 'Your English title',
        'content' => 'Your English content...',
        'excerpt' => 'Your English excerpt...'
    ],
    
    'Autre titre français' => [
        'title' => 'Another English title',
        'content' => 'Another English content...',
        'excerpt' => 'Another English excerpt...'
    ],
];
```

### 5. Traduction directe en base de données

Si vous préférez modifier directement :

```bash
php artisan tinker
```

```php
// Trouver un article par son titre
$article = App\Models\Article::where('title', 'Titre français')->first();

// Le modifier
$article->update([
    'title' => 'English Title',
    'content' => 'English content...',
    'excerpt' => 'English excerpt...'
]);
```

## Conseils pour la traduction

### Structure du contenu
- Gardez la même structure (titres, paragraphes, listes)
- Adaptez les exemples culturels si nécessaire
- Maintenez le ton et le style

### Éléments à traduire
- **Titre** : Titre principal de l'article
- **Contenu** : Corps de l'article complet
- **Extrait** : Résumé court (150 caractères max)
- **Slug** : Se met à jour automatiquement

### Vérification
Après traduction, vérifiez :
1. L'article apparaît correctement sur le site
2. Les liens internes fonctionnent
3. Le formatage est préservé
4. Les métadonnées sont cohérentes

## Commandes utiles

```bash
# Voir tous les articles
php artisan tinker
App\Models\Article::all(['title', 'created_at']);

# Compter les articles
App\Models\Article::count();

# Voir les articles par catégorie
App\Models\Article::with('category')->get()->groupBy('category.name');

# Vider le cache après modifications
php artisan cache:clear
```

## Automatisation future

Pour éviter ce problème à l'avenir, vous pouvez :
1. Créer directement vos articles en anglais
2. Utiliser un système de traduction automatique
3. Créer des templates d'articles bilingues
