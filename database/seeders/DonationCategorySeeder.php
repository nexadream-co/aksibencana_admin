<?php

namespace Database\Seeders;

use App\Models\DonationCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DonationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DonationCategory::create([
             "name" => "Alam"
        ]);

        DonationCategory::create([
             "name" => "Lingkungan"
        ]);

        DonationCategory::create([
             "name" => "Kecelakaan"
        ]);
    }
}
