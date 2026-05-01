<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionGame extends Model
{
    /** @use HasFactory<\Database\Factories\SessionGameFactory> */
    use HasFactory;

    public function session(): BelongsTo
    {
        return $this->belongsTo(ReservationSession::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
