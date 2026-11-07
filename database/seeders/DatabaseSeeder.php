<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(BaseSeeder::class);

        // Compte admin (changez ADMIN_EMAIL / ADMIN_PASSWORD dans .env avant le premier seed en production)
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@darjellaba.ma')],
            ['name' => 'Admin', 'password' => env('ADMIN_PASSWORD', 'ChangeMe-2026!')],
        );

        if (app()->environment('local') || env('SEED_DEMO')) {
            $this->call(DemoSeeder::class);
        }
    }
}
