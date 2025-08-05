<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category; // Assurez-vous que ce chemin est correct
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Exécute les seeders de la base de données pour les catégories.
     *
     * @return void
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Développement Web', 'description' => 'Articles sur le développement frontend et backend.'],
            ['name' => 'Design UI/UX', 'description' => 'Conseils et tendances en design d\'interface utilisateur et expérience utilisateur.'],
            ['name' => 'Marketing Digital', 'description' => 'Stratégies et outils de marketing en ligne.'],
            ['name' => 'Productivité', 'description' => 'Méthodes et astuces pour améliorer l\'efficacité personnelle et professionnelle.'],
            ['name' => 'Actualités Tech', 'description' => 'Les dernières nouvelles et innovations technologiques.'],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['name' => $categoryData['name']],
                [
                    'description' => $categoryData['description'],
                    'slug' => Str::slug($categoryData['name']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        
    }
}
