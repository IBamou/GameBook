<?php

namespace App\Observers;

use App\Events\TableStatusChanged;
use App\Mail\ReservationCancelled;
use App\Mail\ReservationConfirmed;
use App\Mail\ReservationCreated;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;

class ReservationObserver
{
    public function created(Reservation $reservation): void
    {
        if ($reservation->user && $reservation->user->email) {
            Mail::to($reservation->user->email)->send(new ReservationCreated($reservation));
        }

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
            if ($reservation->user && $reservation->user->email) {
                if ($reservation->status === 'confirmed' && $reservation->getOriginal('status') !== 'confirmed') {
                    Mail::to($reservation->user->email)->send(new ReservationConfirmed($reservation));
                } elseif ($reservation->status === 'cancelled') {
                    Mail::to($reservation->user->email)->send(new ReservationCancelled($reservation));
                }
            }

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
        if ($reservation->user && $reservation->user->email) {
            Mail::to($reservation->user->email)->send(new ReservationCancelled($reservation));
        }

        event(new TableStatusChanged(
            $reservation->table_id,
            'available',
            ['reservation_id' => $reservation->id]
        ));
    }
}