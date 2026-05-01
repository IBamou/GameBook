<?php

namespace App\Models;

use Database\Factories\ReservationSessionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReservationSession extends Model
{
    /** @use HasFactory<ReservationSessionFactory> */
    use HasFactory;

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function sessionGames(): HasMany
    {
        return $this->hasMany(SessionGame::class);
    }
}
