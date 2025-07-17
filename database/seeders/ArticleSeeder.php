<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article; // Assurez-vous que ce chemin est correct
use App\Models\Admin;   // Assurez-vous que ce chemin est correct
use App\Models\Category; // Assurez-vous que ce chemin est correct
use App\Models\Tag;     // Assurez-vous que ce chemin est correct
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Exécute les seeders de la base de données pour les articles.
     *
     * @return void
     */
    public function run(): void
    {
        // Assurez-vous qu'il y a au moins un admin, une catégorie et des tags
        $admin = Admin::first();
        if (!$admin) {
            $this->call(AdminSeeder::class);
            $admin = Admin::first();
        }

        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        $tags = Tag::all();
        if ($tags->isEmpty()) {
            $this->call(TagSeeder::class);
            $tags = Tag::all();
        }

        // Créer des articles avec du contenu varié
        $articles = [
            [
                'title' => 'Introduction à Laravel : Le Framework PHP Moderne',
                'content' => 'Laravel est un framework PHP élégant et expressif qui simplifie le développement web. Avec son architecture MVC robuste, Laravel offre des outils puissants comme Eloquent ORM, Blade templating, et Artisan CLI. Ce framework révolutionne la façon dont nous construisons des applications web modernes en PHP.',
                'category' => 'Développement Web',
                'tags' => ['PHP', 'Laravel', 'Backend']
            ],
            [
                'title' => 'Maîtriser JavaScript ES6+ : Les Nouvelles Fonctionnalités',
                'content' => 'JavaScript ES6+ apporte de nombreuses améliorations au langage : arrow functions, destructuring, modules, async/await, et bien plus. Ces fonctionnalités modernes permettent d\'écrire du code plus propre, plus lisible et plus maintenable. Découvrez comment ces outils transforment le développement JavaScript.',
                'category' => 'Développement Web',
                'tags' => ['JavaScript', 'Frontend', 'ES6']
            ],
            [
                'title' => 'Guide Complet de React : Construire des Interfaces Modernes',
                'content' => 'React révolutionne le développement d\'interfaces utilisateur avec ses composants réutilisables et son Virtual DOM. Apprenez les hooks, le state management, et les meilleures pratiques pour créer des applications web interactives et performantes. React est devenu incontournable dans l\'écosystème JavaScript moderne.',
                'category' => 'Développement Web',
                'tags' => ['React', 'JavaScript', 'Frontend']
            ],
            [
                'title' => 'Optimisation des Performances Web : Techniques Avancées',
                'content' => 'L\'optimisation des performances web est cruciale pour l\'expérience utilisateur. Explorez les techniques de lazy loading, compression d\'images, minification CSS/JS, mise en cache, et optimisation du Critical Rendering Path. Ces stratégies peuvent réduire significativement les temps de chargement.',
                'category' => 'Productivité',
                'tags' => ['Performance', 'Optimisation', 'UX']
            ],
            [
                'title' => 'Sécurité Web : Protéger vos Applications contre les Menaces',
                'content' => 'La sécurité web est fondamentale dans le développement moderne. Découvrez comment vous protéger contre XSS, CSRF, injection SQL, et autres vulnérabilités. Implémentez HTTPS, validation des données, authentification robuste, et suivez les meilleures pratiques de sécurité pour protéger vos utilisateurs.',
                'category' => 'Sécurité',
                'tags' => ['Sécurité', 'Cybersécurité', 'HTTPS']
            ],
            [
                'title' => 'API REST avec Node.js : Architecture et Bonnes Pratiques',
                'content' => 'Node.js excelle dans la création d\'APIs REST performantes. Apprenez à structurer vos endpoints, gérer l\'authentification JWT, implémenter la pagination, et optimiser les requêtes de base de données. Une API bien conçue est la fondation d\'applications web modernes et évolutives.',
                'category' => 'Développement Web',
                'tags' => ['Node.js', 'API', 'Backend']
            ],
            [
                'title' => 'CSS Grid et Flexbox : Maîtriser les Layouts Modernes',
                'content' => 'CSS Grid et Flexbox révolutionnent la création de layouts web. Grid excelle pour les structures bidimensionnelles complexes, tandis que Flexbox perfectionne l\'alignement unidimensionnel. Ensemble, ils offrent un contrôle précis sur la disposition, remplaçant les anciennes techniques de float et positioning.',
                'category' => 'Design',
                'tags' => ['CSS', 'Frontend', 'Design']
            ],
            [
                'title' => 'DevOps pour Développeurs : CI/CD et Déploiement Automatisé',
                'content' => 'L\'intégration continue et le déploiement continu (CI/CD) transforment le cycle de développement. Découvrez GitHub Actions, Docker, et les pipelines automatisés. Ces outils permettent des déploiements fiables, des tests automatisés, et une collaboration d\'équipe plus efficace.',
                'category' => 'DevOps',
                'tags' => ['DevOps', 'CI/CD', 'Docker']
            ],
            [
                'title' => 'Progressive Web Apps : L\'Avenir du Web Mobile',
                'content' => 'Les Progressive Web Apps (PWA) combinent le meilleur du web et des applications natives. Avec les Service Workers, le cache intelligent, et les notifications push, les PWAs offrent des expériences utilisateur exceptionnelles, même hors ligne. Elles représentent l\'évolution naturelle du web mobile.',
                'category' => 'Mobile',
                'tags' => ['PWA', 'Mobile', 'Frontend']
            ],
            [
                'title' => 'Intelligence Artificielle et Développement Web : Nouvelles Perspectives',
                'content' => 'L\'IA transforme le développement web avec des outils comme GitHub Copilot, l\'optimisation automatique, et la personnalisation intelligente. Explorez comment intégrer des APIs d\'IA, automatiser les tâches répétitives, et créer des expériences utilisateur adaptatives. L\'IA devient un partenaire indispensable du développeur moderne.',
                'category' => 'IA',
                'tags' => ['IA', 'Machine Learning', 'Innovation']
            ],
            [
                'title' => 'Basketball : L\'Art de la Performance et de la Stratégie Moderne',
                'content' => 'Le basketball moderne a évolué vers un sport ultra-technique où la data analytics révolutionne les stratégies. De la NBA aux championnats européens, découvrez comment les équipes utilisent l\'intelligence artificielle pour analyser les performances, optimiser les tactiques et développer les talents. Les nouvelles technologies transforment la façon dont nous comprenons et pratiquons ce sport spectaculaire.

## L\'Évolution Tactique du Basketball

### La Révolution du Tir à 3 Points
Le basketball moderne privilégie l\'efficacité statistique. Les équipes NBA tirent désormais plus de 40 tirs à 3 points par match, contre 12 en 2000. Cette évolution s\'appuie sur l\'analyse des "expected points per shot" qui démontre la supériorité mathématique du tir longue distance.

### Stratégies Défensives Modernes
- **Switch Defense** : Changements systématiques sur écrans
- **Zone Hybride** : Mélange entre défense de zone et individuelle
- **Help and Recover** : Aide défensive avec retour rapide
- **Analytics-Driven** : Positionnement basé sur les données

## La Data Analytics au Service de la Performance

### Métriques Avancées
Les statistiques traditionnelles (points, rebonds, passes) laissent place à des métriques sophistiquées :
- **Player Efficiency Rating (PER)** : Efficacité globale du joueur
- **True Shooting Percentage** : Pourcentage de tir réel incluant les lancers
- **Win Shares** : Contribution aux victoires de l\'équipe
- **Box Plus/Minus** : Impact sur le différentiel de points

### Technologies de Tracking
- **SportVU Cameras** : Suivi en temps réel des mouvements
- **Wearable Sensors** : Monitoring de la charge d\'entraînement
- **Shot Analytics** : Analyse précise de chaque tir
- **Biomechanical Analysis** : Optimisation des gestes techniques

## Formation et Développement des Talents

### Programmes Jeunes Modernes
Les académies de basketball intègrent désormais :
- **Préparation mentale** : Gestion du stress et concentration
- **Nutrition sportive** : Optimisation des performances
- **Récupération active** : Protocoles de régénération
- **Formation tactique** : Compréhension du jeu moderne

### L\'Impact des Réseaux Sociaux
Les jeunes talents utilisent les plateformes digitales pour :
- Analyser les techniques des professionnels
- Partager leurs performances
- Attirer l\'attention des recruteurs
- Développer leur personal branding

## Le Basketball Français en Pleine Ascension

### Succès Internationaux
La France s\'impose comme une puissance mondiale :
- **Médaille d\'argent olympique 2021** : Performance historique
- **Talents NBA** : Gobert, Fournier, Batum, Wembanyama
- **Championnat d\'Europe** : Régularité au plus haut niveau
- **Formation** : Excellence des centres de formation

### Développement du Basketball Féminin
Le basketball féminin français connaît un essor remarquable :
- **Professionnalisation** : Amélioration des conditions
- **Médiatisation** : Couverture télévisuelle accrue
- **Talents émergents** : Nouvelle génération prometteuse
- **Infrastructures** : Investissements dans les équipements

## L\'Avenir du Basketball

### Innovations Technologiques
- **Réalité Virtuelle** : Entraînement immersif
- **Intelligence Artificielle** : Analyse prédictive des performances
- **Biomécanique 3D** : Optimisation des mouvements
- **Streaming Interactif** : Nouvelle expérience spectateur

### Enjeux Sociétaux
Le basketball moderne s\'engage sur :
- **Inclusion sociale** : Sport accessible à tous
- **Égalité des genres** : Promotion du basketball féminin
- **Développement durable** : Événements éco-responsables
- **Éducation** : Valeurs transmises par le sport

Le basketball continue d\'évoluer, mêlant tradition sportive et innovation technologique pour offrir un spectacle toujours plus captivant et des performances toujours plus élevées.',
                'category' => 'Sport',
                'tags' => ['Basketball', 'Sport', 'Performance']
            ],
        ];

        // Créer les articles
        foreach ($articles as $articleData) {
            $title = $articleData['title'];
            $content = $articleData['content'];
            $excerpt = Str::limit($content, 150); // Extrait de l'article

            // Trouver la catégorie correspondante ou prendre une aléatoire
            $category = $categories->where('name', $articleData['category'])->first() ?? $categories->random();

            $article = Article::create([
                'title' => $title,
                'content' => $content,
                'excerpt' => $excerpt,
                'slug' => Str::slug($title . '-' . Str::random(5)), // Slug unique
                'image' => null, // Pas d'image par défaut
                'published_at' => now(), // Date de publication actuelle
                'status' => 'published', // Tous publiés pour la démo
                'admin_id' => $admin->id, // Assigner à l'admin créé
                'category_id' => $category->id, // Assigner à la catégorie correspondante
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assigner les tags correspondants
            $articleTags = [];
            foreach ($articleData['tags'] as $tagName) {
                $tag = $tags->where('name', $tagName)->first();
                if ($tag) {
                    $articleTags[] = $tag->id;
                }
            }

            if (!empty($articleTags)) {
                $article->tags()->attach($articleTags, ['assigned_at' => now()]);
            }
        }

      
    }
}
