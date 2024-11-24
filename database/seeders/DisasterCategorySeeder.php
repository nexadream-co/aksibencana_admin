<?php

namespace Database\Seeders;

use App\Models\DisasterCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisasterCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DisasterCategory::create([
            "name" => "Alam"
        ]);

        DisasterCategory::create([
            "name" => "Lingkungan"
        ]);

        DisasterCategory::create([
            "name" => "Kecelakaan"
        ]);
    }
}
