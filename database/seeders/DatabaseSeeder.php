<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed admin user
        $this->call(AdminSeeder::class);

        // Seed categories
        $this->call(CategorySeeder::class);

        // Seed tags
        $this->call(TagSeeder::class);

        // Seed articles (optional - for demo)
        $this->call(ArticleSeeder::class);
    }
}
