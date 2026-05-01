<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    /** @use HasFactory<\Database\Factories\TableFactory> */
    use HasFactory;

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function todayReservations()
    {
        return $this->reservations()->whereDate('date', today());
    }

    public function getTodayReservationAttribute()
    {
        return $this->todayReservations()
            ->where('status', 'confirmed')
            ->whereTime('start_time', '>', now())
            ->orderBy('start_time')
            ->first();
    }

    public function getCurrentSessionAttribute()
    {
        return $this->reservations()
            ->whereHas('sessions', function ($query) {
                $query->where('status', 'active');
            })
            ->first()?->sessions()->where('status', 'active')->first();
    }

    public function getStatusAttribute(): string
    {
        // Case 1: Session is active
        if ($this->currentSession) {
            return 'in_progress';
        }

        // Case 2: Time reached - ready to start
        $nextReservation = $this->todayReservation;
        if ($nextReservation && Carbon::parse($nextReservation->start_time)->isBefore(now())) {
            return 'ready';
        }

        // Case 3: Confirmed reservation exists
        if ($nextReservation) {
            return 'booked';
        }

        // Case 4: Table is free
        return 'available';
    }
}
