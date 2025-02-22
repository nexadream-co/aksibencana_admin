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
        $this->call([
            UserSeeder::class,
            AbilitySeeder::class,
            BranchOfficeSeeder::class,
            EducationSeeder::class,
            DisasterCategorySeeder::class,
            DonationCategorySeeder::class
        ]);
    }
}
