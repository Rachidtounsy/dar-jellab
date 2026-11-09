<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([BaseSeeder::class, ArticleSeeder::class]);

        // Compte admin (changez ADMIN_EMAIL / ADMIN_PASSWORD dans .env avant le premier seed en production)
        User::firstOrCreate(
            ['email' => config('shop.admin_email')],
            ['name' => 'Admin', 'password' => config('shop.admin_password')],
        );

        if (app()->environment('local') || config('shop.seed_demo')) {
            $this->call(DemoSeeder::class);
        }
    }
}
