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
        $strategyGames = Category::where('name', 'Strategy Games')->first();
        $partyGames = Category::where('name', 'Party Games')->first();
        $familyGames = Category::where('name', 'Family Games')->first();

        $games = [
            // Board Games
            ['name' => 'Chess', 'description' => 'Classic two-player strategy board game', 'difficulty' => 'hard', 'min_players' => 2, 'max_players' => 2, 'spots' => 2, 'duration' => 60, 'status' => 'available', 'category_id' => $boardGames->id, 'price' => 25.00],
            ['name' => 'Monopoly', 'description' => 'Classic real estate board game', 'difficulty' => 'medium', 'min_players' => 2, 'max_players' => 8, 'spots' => 6, 'duration' => 120, 'status' => 'available', 'category_id' => $boardGames->id, 'price' => 35.00],
            ['name' => 'Scrabble', 'description' => 'Word-building classic board game', 'difficulty' => 'medium', 'min_players' => 2, 'max_players' => 4, 'spots' => 4, 'duration' => 90, 'status' => 'available', 'category_id' => $boardGames->id, 'price' => 30.00],
            ['name' => 'Risk', 'description' => 'Global domination strategy game', 'difficulty' => 'hard', 'min_players' => 2, 'max_players' => 6, 'spots' => 6, 'duration' => 180, 'status' => 'available', 'category_id' => $boardGames->id, 'price' => 40.00],
            ['name' => 'Clue', 'description' => 'Classic mystery detective game', 'difficulty' => 'easy', 'min_players' => 3, 'max_players' => 6, 'spots' => 5, 'duration' => 60, 'status' => 'available', 'category_id' => $boardGames->id, 'price' => 25.00],
            
            // Card Games
            ['name' => 'Uno', 'description' => 'Fun card game for family', 'difficulty' => 'easy', 'min_players' => 2, 'max_players' => 10, 'spots' => 8, 'duration' => 30, 'status' => 'available', 'category_id' => $cardGames->id, 'price' => 15.00],
            ['name' => 'Cards Against Humanity', 'description' => 'Hilarious party card game', 'difficulty' => 'easy', 'min_players' => 3, 'max_players' => 10, 'spots' => 8, 'duration' => 45, 'status' => 'available', 'category_id' => $cardGames->id, 'price' => 25.00],
            ['name' => 'Exploding Kittens', 'description' => 'Party card game with kittens', 'difficulty' => 'easy', 'min_players' => 2, 'max_players' => 5, 'spots' => 4, 'duration' => 20, 'status' => 'available', 'category_id' => $cardGames->id, 'price' => 20.00],
            ['name' => 'Dobble', 'description' => 'Speed matching card game', 'difficulty' => 'easy', 'min_players' => 2, 'max_players' => 8, 'spots' => 6, 'duration' => 15, 'status' => 'available', 'category_id' => $cardGames->id, 'price' => 18.00],
            
            // Strategy Games
            ['name' => 'Catan', 'description' => 'Settlements building strategy game', 'difficulty' => 'medium', 'min_players' => 3, 'max_players' => 4, 'spots' => 4, 'duration' => 90, 'status' => 'available', 'category_id' => $strategyGames->id, 'price' => 45.00],
            ['name' => 'Ticket to Ride', 'description' => 'Cross-country train adventure', 'difficulty' => 'easy', 'min_players' => 2, 'max_players' => 5, 'spots' => 4, 'duration' => 60, 'status' => 'available', 'category_id' => $strategyGames->id, 'price' => 40.00],
            ['name' => 'Carcassonne', 'description' => 'Tile-placement strategy game', 'difficulty' => 'easy', 'min_players' => 2, 'max_players' => 5, 'spots' => 4, 'duration' => 45, 'status' => 'available', 'category_id' => $strategyGames->id, 'price' => 35.00],
            ['name' => 'Patchwork', 'description' => 'Strategic quilting game', 'difficulty' => 'medium', 'min_players' => 2, 'max_players' => 2, 'spots' => 2, 'duration' => 30, 'status' => 'available', 'category_id' => $strategyGames->id, 'price' => 30.00],
            ['name' => 'Azul', 'description' => 'Tile drafting strategy game', 'difficulty' => 'medium', 'min_players' => 2, 'max_players' => 4, 'spots' => 4, 'duration' => 45, 'status' => 'available', 'category_id' => $strategyGames->id, 'price' => 35.00],
            
            // Party Games
            ['name' => 'Jackbox Party Pack', 'description' => 'Multiplayer party game collection', 'difficulty' => 'easy', 'min_players' => 3, 'max_players' => 8, 'spots' => 8, 'duration' => 60, 'status' => 'available', 'category_id' => $partyGames->id, 'price' => 35.00],
            ['name' => 'Codenames', 'description' => 'Word guessing party game', 'difficulty' => 'easy', 'min_players' => 4, 'max_players' => 8, 'spots' => 6, 'duration' => 30, 'status' => 'available', 'category_id' => $partyGames->id, 'price' => 25.00],
            ['name' => 'Just One', 'description' => 'Cooperative word guessing game', 'difficulty' => 'easy', 'min_players' => 3, 'max_players' => 7, 'spots' => 6, 'duration' => 20, 'status' => 'available', 'category_id' => $partyGames->id, 'price' => 20.00],
            
            // Family Games
            ['name' => 'Jenga', 'description' => 'Classic tower stacking game', 'difficulty' => 'easy', 'min_players' => 1, 'max_players' => 6, 'spots' => 4, 'duration' => 20, 'status' => 'available', 'category_id' => $familyGames->id, 'price' => 15.00],
            ['name' => 'Twister', 'description' => 'Classic physical party game', 'difficulty' => 'easy', 'min_players' => 2, 'max_players' => 9, 'spots' => 6, 'duration' => 15, 'status' => 'available', 'category_id' => $familyGames->id, 'price' => 20.00],
            ['name' => 'UNO Flip', 'description' => 'Classic UNO with flip variant', 'difficulty' => 'easy', 'min_players' => 2, 'max_players' => 10, 'spots' => 8, 'duration' => 30, 'status' => 'available', 'category_id' => $familyGames->id, 'price' => 15.00],
        ];

        foreach ($games as $game) {
            Game::create($game);
        }
    }
}