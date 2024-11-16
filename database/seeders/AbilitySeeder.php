<?php

namespace Database\Seeders;

use App\Models\Ability;
use Illuminate\Database\Seeder;

class AbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ability::create([
            "name" => "Kesehatan"
        ]);

        Ability::create([
            "name" => "Trauma Healing"
        ]);
    }
}
