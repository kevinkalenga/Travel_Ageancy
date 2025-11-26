<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appel de tous les seeders nécessaires
        $this->call([
            AdminSeeder::class,
            WelcomeItemSeeder::class,
            CounterItemSeeder::class,
            HomeItemSeeder::class,
            AboutItemSeeder::class,
            ContactItemSeeder::class,
            TermPrivacyItemSeeder::class,
        ]);

        // Si tu veux générer des utilisateurs factices :
        // \App\Models\User::factory(10)->create();
    }
}

