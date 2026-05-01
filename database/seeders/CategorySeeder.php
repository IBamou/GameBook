<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['name' => 'Board Games', 'description' => 'Classic board games for all ages']);
        Category::create(['name' => 'Card Games', 'description' => 'Fun card games']);
        Category::create(['name' => 'Strategy Games', 'description' => 'Brain teasers and strategy games']);
    }
}
