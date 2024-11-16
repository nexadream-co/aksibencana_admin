<?php

namespace Database\Seeders;

use App\Models\BranchOffice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BranchOffice::create([
            "district_id" => 3578040,
            "name" => "Surabaya",
            "address" => "Jl. HR Muhammad",
            "status" => "active",
            "latitude" => 67543,
            "longitude" => 454333
        ]);

        BranchOffice::create([
            "district_id" => 3276011,
            "name" => "Depok",
            "address" => "Jl. Hayam Wuruk",
            "status" => "active",
            "latitude" => 3243534,
            "longitude" => 3745544
        ]);
    }
}
