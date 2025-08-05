<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Visiteur; // Assurez-vous que ce chemin est correct
use App\Models\Article;   // Assurez-vous que ce chemin est correct
use Illuminate\Support\Str;

class VisiteurSeeder extends Seeder
{
    /**
     * Exécute les seeders de la base de données pour les visiteurs.
     *
     * @return void
     */
    public function run(): void
    {
        $articles = Article::where('status', 'published')->get();

        if ($articles->isEmpty()) {
            $this->call(ArticleSeeder::class);
            $articles = Article::where('status', 'published')->get();
        }

        if ($articles->isEmpty()) {
            echo "Aucun article publié trouvé pour créer des visites.\n";
            return;
        }

        // Créer 100 visites de démonstration
        for ($i = 1; $i <= 100; $i++) {
            $randomArticle = $articles->random(); // Choisir un article publié aléatoire

            Visiteur::create([
                'ip_address' => '192.168.' . rand(0, 255) . '.' . rand(0, 255),
                'session_id' => Str::random(32),
                'visit_date' => now()->subHours(rand(1, 720)), // Visites sur le dernier mois
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/' . rand(80, 100) . '.0.0.0 Safari/537.36',
                'article_id' => $randomArticle->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
