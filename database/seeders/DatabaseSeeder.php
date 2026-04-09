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
        // Call the admin seeder
        $this->call([
            AdminSeeder::class,
            ExpenseCategorySeeder::class,
        ]);
    }
}
