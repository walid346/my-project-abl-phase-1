<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $admin = Admin::firstOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'username' => 'admin',
                    'password' => Hash::make('password123'),
                ]
            );

            if ($admin->wasRecentlyCreated) {
                $this->info('Admin user created successfully!');
                $this->info('Email: admin@example.com');
                $this->info('Password: password123');
            } else {
                $this->info('Admin user already exists!');
                $this->info('Email: admin@example.com');
            }
        } catch (\Exception $e) {
            $this->error('Error creating admin: ' . $e->getMessage());
        }
    }
}
