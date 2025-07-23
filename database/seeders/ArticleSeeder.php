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
                'title' => 'Introduction to Laravel: The Modern PHP Framework',
                'content' => 'Laravel is an elegant and expressive PHP framework that simplifies web development. With its robust MVC architecture, Laravel offers powerful tools such as Eloquent ORM, Blade templating, and Artisan CLI. This framework revolutionizes the way we build modern web applications in PHP.',
                'category' => 'Web Development',
                'tags' => ['PHP', 'Laravel', 'Backend']
            ],
            [
                'title' => 'Mastering JavaScript ES6+: The New Features',
                'content' => 'JavaScript ES6+ brings a host of improvements to the language: arrow functions, destructuring, modules, async/await, and much more. These modern features allow you to write cleaner, more readable and more maintainable code. Discover how these tools are transforming JavaScript development.',
                'category' => 'Web Development',
                'tags' => ['JavaScript', 'Frontend', 'ES6']
            ],
            [
                'title' => 'Complete Guide to React: Building Modern Interfaces',
                'content' => 'React revolutionizes user interface development with its reusable components and Virtual DOM. Learn hooks, state management and best practices for creating high-performance, interactive web applications. React has become an essential part of the modern JavaScript ecosystem.',
                'category' => 'Web Development',
                'tags' => ['React', 'JavaScript', 'Frontend']
            ],
            [
                'title' => 'Web Performance Optimization: Advanced Techniques',
                'content' => 'Web performance is crucial for user experience and SEO. Learn advanced optimization techniques: lazy loading, code splitting, image optimization, caching strategies, and performance monitoring. Discover how to create lightning-fast web applications that delight users.',
                'category' => 'Web Development',
                'tags' => ['Performance', 'Optimization', 'Frontend']
            ],
            [
                'title' => 'Web Security: Protecting your Applications from Threats',
                'content' => 'Web security is fundamental to modern development. Find out how to protect against XSS, CSRF, SQL injection and other vulnerabilities. Implement HTTPS, data validation, strong authentication, and follow security best practices to protect your users.',
                'category' => 'Security',
                'tags' => ['Security', 'Cybersecurity', 'HTTPS']
            ],
            [
                'title' => 'API REST with Node.js: Architecture and Best Practices',
                'content' => 'Node.js excels at creating high-performance REST APIs. Learn how to structure your endpoints, manage JWT authentication, implement pagination, and optimize database queries. A well-designed API is the foundation of modern, scalable web applications.',
                'category' => 'Web Development',
                'tags' => ['Node.js', 'API', 'Backend']
            ],
            [
                'title' => 'CSS Grid and Flexbox: Mastering Modern Layouts',
                'content' => 'CSS Grid and Flexbox are revolutionizing the creation of web layouts. Grid excels at complex two-dimensional structures, while Flexbox perfects one-dimensional alignment. Together, they offer precise control over layout, replacing older float and positioning techniques.',
                'category' => 'Design',
                'tags' => ['CSS', 'Frontend', 'Design']
            ],
            [
                'title' => 'DevOps for Developers: CI/CD and Automated Deployment',
                'content' => 'Continuous integration and continuous deployment (CI/CD) are transforming the development cycle. Discover GitHub Actions, Docker, and automated pipelines. These tools enable reliable deployments, automated testing, and more effective team collaboration.',
                'category' => 'DevOps',
                'tags' => ['DevOps', 'CI/CD', 'Docker']
            ],
            [
                'title' => 'Progressive Web Apps: The Future of the Mobile Web',
                'content' => 'Progressive Web Apps (PWAs) combine the best of the web and native applications. With Service Workers, intelligent caching and push notifications, PWAs offer exceptional user experiences, even offline. They represent the natural evolution of the mobile web.',
                'category' => 'Mobile',
                'tags' => ['PWA', 'Mobile', 'Frontend']
            ],
            [
                'title' => 'Artificial Intelligence and Web Development: New Perspectives',
                'content' => 'AI is transforming web development with tools like GitHub Copilot, automatic optimization, and intelligent personalization. Explore how to integrate AI APIs, automate repetitive tasks, and create adaptive user experiences. AI is becoming an indispensable partner for the modern developer.',
                'category' => 'AI',
                'tags' => ['AI', 'Machine Learning', 'Innovation']
            ],
            [
                'title' => 'Basketball: The Art of Modern Performance and Strategy',
                'content' => 'Modern basketball has evolved into an ultra-technical sport where data analytics is revolutionizing strategies. From the NBA to the European leagues, discover how teams are using artificial intelligence to analyze performance, optimize tactics and develop talent. New technologies are transforming the way we understand and play this spectacular sport.

## The Tactical Evolution of Basketball

### The 3-Point Shooting Revolution
Modern basketball is all about statistical efficiency. NBA teams now shoot more than 40 3-pointers per game, up from 12 in 2000. This evolution is based on "expected points per shot" analysis, which demonstrates the mathematical superiority of long-range shooting.

### Modern Defensive Strategies
- **Switch Defense**: Systematic screen changes
- **Zone Hybrid**: Mixture of zone and individual defense
- **Help and Recover**: Defensive help with quick return
- **Analytics-Driven**: Data-driven positioning

## Data Analytics for Performance

### Advanced Metrics
Traditional statistics (points, rebounds, assists) give way to sophisticated metrics:
- **Player Efficiency Rating (PER)**: Overall player efficiency
- **True Shooting Percentage**: Percentage of true shooting, including throws
- **Win Shares**: Contribution to team wins
- **Box Plus/Minus**: Impact on points differential

### Tracking Technologies
- **SportVU Cameras**: Real-time movement tracking
- **Wearable Sensors**: Training load monitoring
- **Shot Analytics**: Precise analysis of each shot
- **Biomechanical Analysis**: Optimization of technical gestures

## Training and Talent Development

### Modern Youth Programs
Basketball academies now include:
- **Mental Preparation**: Stress management and concentration
- **Sports Nutrition**: Optimizing performance
- **Active Recovery**: Regeneration protocols
- **Tactical Training**: Understanding the modern game

### The Impact of Social Networks
Young talents use digital platforms to:
- Analyze professional techniques
- Share their performances
- Attract the attention of recruiters
- Develop their personal branding

## French Basketball on the Rise

### International Success
France establishes itself as a world power:
- **2021 Olympic Silver Medal**: Historic performance
- **NBA Talents**: Gobert, Fournier, Batum, Wembanyama
- **European Championship**: Consistency at the highest level
- **Training**: Excellence of training centers

### Women\'s Basketball Development
French women\'s basketball is undergoing remarkable growth:
- **Professionalization**: Improved conditions
- **Media Coverage**: Increased television coverage
- **Emerging Talents**: Promising new generation
- **Infrastructure**: Investment in equipment

## The Future of Basketball

### Technological Innovations
- **Virtual Reality**: Immersive training
- **Artificial Intelligence**: Predictive performance analysis
- **3D Biomechanics**: Motion optimization
- **Interactive Streaming**: New spectator experience

### Societal Challenges
Modern basketball is committed to:
- **Social Inclusion**: Sport accessible to all
- **Gender Equality**: Promotion of women\'s basketball
- **Sustainable Development**: Eco-responsible events
- **Education**: Values transmitted through sport

Basketball continues to evolve, combining sporting tradition and technological innovation to offer an ever more captivating spectacle and ever higher performances.',
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
