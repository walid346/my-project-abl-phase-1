<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag; // Assurez-vous que ce chemin est correct
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Exécute les seeders de la base de données pour les tags.
     *
     * @return void
     */
    public function run(): void
    {
        $tags = [
            'Laravel', 'PHP', 'JavaScript', 'React', 'Vue.js',
            'CSS', 'HTML', 'SEO', 'Content Marketing', 'IA',
            'Cloud', 'Sécurité', 'Base de données', 'Mobile', 'API',
        ];

        foreach ($tags as $tagName) {
            Tag::firstOrCreate(
                ['name' => $tagName],
                [
                    'slug' => Str::slug($tagName),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

       
    }
}
