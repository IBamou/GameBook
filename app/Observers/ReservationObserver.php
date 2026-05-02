<?php

namespace App\Observers;

use App\Events\TableStatusChanged;
use App\Models\Reservation;

class ReservationObserver
{
    public function created(Reservation $reservation): void
    {
        if ($reservation->status === 'confirmed') {
            event(new TableStatusChanged(
                $reservation->table_id,
                'booked',
                [
                    'reservation_id' => $reservation->id,
                    'starts_at' => $reservation->start_time,
                    'ends_at' => $reservation->end_time,
                ]
            ));
        }
    }

    public function updated(Reservation $reservation): void
    {
        if ($reservation->wasChanged('status')) {
            $newStatus = match ($reservation->status) {
                'cancelled' => 'available',
                'confirmed' => 'booked',
                default => $reservation->status,
            };

            event(new TableStatusChanged(
                $reservation->table_id,
                $newStatus,
                ['reservation_id' => $reservation->id]
            ));
        }
    }

    public function deleted(Reservation $reservation): void
    {
        event(new TableStatusChanged(
            $reservation->table_id,
            'available',
            ['reservation_id' => $reservation->id]
        ));
    }
}