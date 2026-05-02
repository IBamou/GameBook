<?php

namespace App\Console\Commands;

use App\Events\TableStatusChanged;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:check-table-status')]
#[Description('Check table status and broadcast updates based on time')]
class CheckTableStatus extends Command
{
    public function handle()
    {
        $this->checkStatuses();
        
        $this->info('Completed table status checks at ' . now()->format('Y-m-d H:i:s'));
    }

    private function checkStatuses()
    {
        $today = Carbon::today();
        $now = Carbon::now();

        // Check for expired sessions (active sessions whose duration has passed)
        $activeSessions = \App\Models\ReservationSession::where('status', 'active')
            ->whereNotNull('started_at')
            ->get();

        foreach ($activeSessions as $session) {
            $reservation = $session->reservation;
            $startedAt = Carbon::parse($session->started_at);
            $expectedEnd = $startedAt->addMinutes($session->duration);
            
            // If session duration has passed
            if ($now->gte($expectedEnd)) {
                // End the session
                $session->update([
                    'status' => 'ended',
                    'ended_at' => $now,
                ]);
                
                // Check for next reservation on same table
                $nextReservation = \App\Models\Reservation::where('table_id', $reservation->table_id)
                    ->where('status', 'confirmed')
                    ->whereDate('date', $today)
                    ->where('start_time', '>', $reservation->end_time)
                    ->orderBy('start_time')
                    ->first();
                
                // Determine new status
                $newStatus = $nextReservation ? 'booked' : 'available';
                
                event(new TableStatusChanged(
                    $reservation->table_id,
                    $newStatus,
                    [
                        'session_id' => $session->id,
                        'reservation_id' => $reservation->id,
                        'reason' => 'session_expired',
                        'next_reservation_id' => $nextReservation?->id,
                    ]
                ));
            }
        }

        // Check for reservations that should be ready
        $reservations = Reservation::where('status', 'confirmed')
            ->whereDate('date', $today)
            ->get();

        foreach ($reservations as $reservation) {
            $startTime = Carbon::parse($reservation->date . ' ' . $reservation->start_time);
            $endTime = Carbon::parse($reservation->date . ' ' . $reservation->end_time);

            if ($endTime->isBefore($startTime)) {
                $endTime->addDay();
            }

            // Check if session should be ready (time reached)
            if ($now->gte($startTime) && $now->lt($endTime)) {
                if (!$reservation->sessions()->where('status', 'active')->exists()) {
                    event(new TableStatusChanged(
                        $reservation->table_id,
                        'ready',
                        [
                            'reservation_id' => $reservation->id,
                            'starts_at' => $reservation->start_time,
                            'ends_at' => $reservation->end_time,
                        ]
                    ));
                }
            }

            // Check if reservation time has completely passed
            if ($now->gte($endTime)) {
                $activeSession = $reservation->sessions()->where('status', 'active')->first();
                if (!$activeSession) {
                    // Check for next reservation
                    $nextReservation = Reservation::where('table_id', $reservation->table_id)
                        ->where('status', 'confirmed')
                        ->whereDate('date', $today)
                        ->where('start_time', '>', $reservation->end_time)
                        ->orderBy('start_time')
                        ->first();
                    
                    $newStatus = $nextReservation ? 'booked' : 'available';
                    
                    event(new TableStatusChanged(
                        $reservation->table_id,
                        $newStatus,
                        [
                            'reservation_id' => $reservation->id,
                            'reason' => 'reservation_ended',
                            'next_reservation_id' => $nextReservation?->id,
                        ]
                    ));
                }
            }
        }
    }
}
