<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $boardGames = Category::where('name', 'Board Games')->first();
        $cardGames = Category::where('name', 'Card Games')->first();

        Game::create([
            'name' => 'Chess',
            'description' => 'Classic two-player strategy board game',
            'difficulty' => 'hard',
            'min_players' => 2,
            'max_players' => 2,
            'spots' => 2,
            'duration' => 60,
            'status' => 'available',
            'category_id' => $boardGames->id,
            'price' => 25.00,
        ]);

        Game::create([
            'name' => 'Uno',
            'description' => 'Fun card game for family',
            'difficulty' => 'easy',
            'min_players' => 2,
            'max_players' => 10,
            'spots' => 8,
            'duration' => 30,
            'status' => 'available',
            'category_id' => $cardGames->id,
            'price' => 15.00,
        ]);

        Game::create([
            'name' => 'Monopoly',
            'description' => 'Classic real estate board game',
            'difficulty' => 'medium',
            'min_players' => 2,
            'max_players' => 8,
            'spots' => 6,
            'duration' => 120,
            'status' => 'available',
            'category_id' => $boardGames->id,
            'price' => 35.00,
        ]);
    }
}
