<?php

namespace Database\Seeders;

use App\Models\ReservationSession;
use App\Models\Reservation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ReservationSessionSeeder extends Seeder
{
    public function run(): void
    {
        $reservation = Reservation::where('status', 'confirmed')->first();
        
        if ($reservation) {
            $startTime = \Carbon\Carbon::parse($reservation->start_time);
            $endTime = \Carbon\Carbon::parse($reservation->end_time);
            $duration = $startTime->diffInMinutes($endTime);

            ReservationSession::create([
                'reservation_id' => $reservation->id,
                'duration' => $duration,
                'status' => 'inactive',
            ]);
        }
    }
}
