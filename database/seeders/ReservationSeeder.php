<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Table;
use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'ilyas@gmail.com')->first();
        $table1 = Table::where('reference', 'T1')->first();
        $table2 = Table::where('reference', 'T2')->first();
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
