<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Board Games', 'description' => 'Classic board games for all ages'],
            ['name' => 'Card Games', 'description' => 'Fun card games for family and friends'],
            ['name' => 'Strategy Games', 'description' => 'Brain teasers and strategic thinking games'],
            ['name' => 'Party Games', 'description' => 'Fun games for large groups and parties'],
            ['name' => 'Family Games', 'description' => 'Games suitable for the whole family'],
            ['name' => 'Adventure', 'description' => 'Thematic adventure and exploration games'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
