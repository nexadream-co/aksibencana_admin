<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Education::create([
            "title" => "Lorem ipsum dolor sit amet",
            "banner" => "https://neilpatel.com/wp-content/uploads/2021/02/ExamplesofSuccessfulBannerAdvertising.jpg",
            "contents" => "Aliqua do laboris cupidatat dolore eiusmod nulla elit reprehenderit ullamco excepteur dolore eiusmod. Est dolore excepteur consectetur duis aute labore veniam excepteur exercitation elit tempor laborum. Eu officia culpa et enim dolor aliqua ipsum Lorem fugiat adipisicing eiusmod laboris sit qui. Occaecat ullamco in elit non consequat. Fugiat quis adipisicing dolor consectetur aute eu amet dolore laborum magna incididunt ullamco."
        ]);

        Education::create([
            "title" => "Dolor sit amet lorem ipsum",
            "banner" => "https://profitsocial.com/blog/wp-content/uploads/profitsocial/web-banners-ads.jpg",
            "contents" => "Eu eu elit elit id fugiat est anim nisi ad dolore duis ad. Irure ullamco enim duis esse. Ullamco anim voluptate incididunt et voluptate veniam cupidatat dolor."
        ]);
    }
}
