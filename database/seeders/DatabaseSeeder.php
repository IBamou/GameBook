<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Game;
use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '+212667676927',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'ilyas',
            'email' => 'ilyas@gmail.com',
            'password' => Hash::make('ilyas123'),
            'role' => 'user',
            'phone' => '+212667676927',
            'email_verified_at' => now(),
        ]);

        // Categories
        $boardGames = Category::create(['name' => 'Board Games', 'description' => 'Classic board games for all ages']);
        $cardGames = Category::create(['name' => 'Card Games', 'description' => 'Fun card games']);
        $strategyGames = Category::create(['name' => 'Strategy Games', 'description' => 'Brain teasers and strategy games']);

        // Games
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

        // Tables
        $table1 = Table::create(['capacity' => 4, 'reference' => 'T1']);
        $table2 = Table::create(['capacity' => 6, 'reference' => 'T2']);
        $table3 = Table::create(['capacity' => 8, 'reference' => 'T3']);

        // Reservations for today
        $user = User::where('email', 'ilyas@gmail.com')->first();
        $game = Game::first();

        Reservation::create([
            'user_id' => $user->id,
            'table_id' => $table1->id,
            'game_id' => $game->id,
            'date' => Carbon::today(),
            'start_time' => Carbon::now()->addHours(2),
            'end_time' => Carbon::now()->addHours(3),
            'spots' => 2,
            'status' => 'confirmed',
            'price' => $game->price,
        ]);

        Reservation::create([
            'user_id' => $user->id,
            'table_id' => $table2->id,
            'game_id' => $game->id,
            'date' => Carbon::today(),
            'start_time' => Carbon::now()->addHours(4),
            'end_time' => Carbon::now()->addHours(5),
            'spots' => 4,
            'status' => 'pending',
            'price' => $game->price,
        ]);
    }
}
