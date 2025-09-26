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
        // Run seeders in order (important for foreign keys)
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            TableSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
