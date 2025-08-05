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
            ['name' => 'Web Development', 'description' => 'Articles on frontend and backend development.'],
            ['name' => 'UI/UX Design', 'description' => 'Tips and trends in user interface and user experience design.'],
            ['name' => 'Digital Marketing', 'description' => 'Online marketing strategies and tools.'],
            ['name' => 'Productivity', 'description' => 'Methods and tips to improve personal and professional efficiency.'],
            ['name' => 'Tech News', 'description' => 'The latest news and technological innovations.'],
            ['name' => 'Security', 'description' => 'Cybersecurity and web security best practices.'],
            ['name' => 'DevOps', 'description' => 'Development operations and deployment strategies.'],
            ['name' => 'Mobile', 'description' => 'Mobile development and progressive web apps.'],
            ['name' => 'AI', 'description' => 'Artificial intelligence and machine learning.'],
            ['name' => 'Sport', 'description' => 'Sports analysis and performance insights.'],
            ['name' => 'Aviation', 'description' => 'Aviation industry and air transport innovations.'],
            ['name' => 'Design', 'description' => 'Web design and visual design principles.']
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
