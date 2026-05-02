<?php

namespace App\Models;

use Database\Factories\ReservationSessionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['reservation_id', 'duration', 'status', 'started_at', 'ended_at', 'current_game_id', 'additional_charges'])]
class ReservationSession extends Model
{
    /** @use HasFactory<ReservationSessionFactory> */
    use HasFactory;

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function currentGame(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'current_game_id');
    }

    /**
     * Check if a game is available during the remaining session time
     */
    public function isGameUnavailable(int $gameId): bool
    {
        $reservation = $this->reservation;
        $now = now();
        $sessionEndTime = $this->started_at->copy()->addMinutes($this->duration);

        return \App\Models\Reservation::where('game_id', $gameId)
            ->whereIn('status', ['confirmed'])
            ->where('id', '!=', $reservation->id)
            ->whereDate('date', $reservation->date)
            ->where(function ($query) use ($now, $sessionEndTime) {
                $query->whereTime('start_time', '<', $sessionEndTime->format('H:i:s'))
                    ->whereTime('end_time', '>', $now->format('H:i:s'));
            })
            ->exists();
    }
}