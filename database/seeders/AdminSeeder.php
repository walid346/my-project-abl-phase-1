<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin; // Assurez-vous que ce chemin est correct
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Exécute les seeders de la base de données pour les administrateurs.
     *
     * @return void
     */
    public function run(): void
    {
        // Créer un administrateur par défaut
        Admin::firstOrCreate(
            ['email' => 'admin@example.com'], // Cherche par email pour éviter les doublons
            [
                'username' => 'superadmin',
                'password' => Hash::make('password'), // Mot de passe par défaut: 'password'
            ]
        );

        
    }
}
