<?php

namespace App\Observers;

use App\Events\TableStatusChanged;
use App\Models\ReservationSession;

class ReservationSessionObserver
{
    public function created(ReservationSession $session): void
    {
        $reservation = $session->reservation;
        event(new TableStatusChanged(
            $reservation->table_id,
            'in_progress',
            [
                'session_id' => $session->id,
                'duration' => $session->duration,
            ]
        ));
    }

    public function updated(ReservationSession $session): void
    {
        if ($session->wasChanged('status') && $session->status === 'ended') {
            $reservation = $session->reservation;
            event(new TableStatusChanged(
                $reservation->table_id,
                'available',
                ['session_id' => $session->id]
            ));
        }
    }
}