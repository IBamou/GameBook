<?php

namespace App\Models;

use Database\Factories\ReservationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ReservationSession;
use Carbon\Carbon;

#[Fillable(['user_id', 'table_id', 'game_id', 'date', 'start_time', 'end_time', 'spots', 'status', 'price'])]
class Reservation extends Model
{
    /** @use HasFactory<ReservationFactory> */
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ReservationSession::class);
    }

    public function createSessionIfConfirmed(): void
    {
        if ($this->status === 'confirmed' && !$this->sessions()->exists()) {
            $startTime = \Carbon\Carbon::parse($this->start_time);
            $endTime = \Carbon\Carbon::parse($this->end_time);
            $duration = $startTime->diffInMinutes($endTime);

            ReservationSession::create([
                'reservation_id' => $this->id,
                'duration' => $duration,
                'status' => 'inactive',
            ]);
        }
    }

    public function cancelSessionIfCancelled(): void
    {
        if ($this->status === 'cancelled') {
            $this->sessions()->delete();
        }
    }

    public function isTimeReached(): bool
    {
        return Carbon::parse($this->start_time)->isBefore(now());
    }

    public function isEnded(): bool
    {
        return Carbon::parse($this->end_time)->isBefore(now());
    }
}
