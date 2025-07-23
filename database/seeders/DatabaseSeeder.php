<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
      
        $this->call(AdminSeeder::class);

       
        $this->call(CategorySeeder::class);

       
        $this->call(TagSeeder::class);

       
        $this->call(ArticleSeeder::class);
    }
}
