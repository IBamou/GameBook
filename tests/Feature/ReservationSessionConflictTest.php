<?php

use App\Models\Reservation;
use App\Models\ReservationSession;
use App\Models\Table;
use App\Models\User;
use App\Models\Game;

it('prevents reservation when table has active session', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $table = Table::factory()->create();
    $game = Game::factory()->create();

    $reservation = Reservation::create([
        'user_id' => $otherUser->id,
        'table_id' => $table->id,
        'game_id' => $game->id,
        'date' => '2026-05-10',
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
        'spots' => 4,
        'status' => 'confirmed',
        'price' => 100,
    ]);

    ReservationSession::create([
        'reservation_id' => $reservation->id,
        'duration' => 120,
        'status' => 'active',
        'started_at' => now(),
    ]);

    $response = $this->actingAs($user)->post(route('reservations.store'), [
        'table_id' => $table->id,
        'game_id' => $game->id,
        'date' => '2026-05-10',
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
        'spots' => 4,
    ]);

    $response->assertSessionHasErrors('time');
    expect(session('errors')->first('time'))->toContain('already reserved');
});

it('allows reservation after session ends', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $table = Table::factory()->create();
    $game = Game::factory()->create();

    $reservation = Reservation::create([
        'user_id' => $otherUser->id,
        'table_id' => $table->id,
        'game_id' => $game->id,
        'date' => '2026-05-10',
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
        'spots' => 4,
        'status' => 'confirmed',
        'price' => 100,
    ]);

    ReservationSession::create([
        'reservation_id' => $reservation->id,
        'duration' => 120,
        'status' => 'ended',
        'started_at' => now()->subHours(2),
        'ended_at' => now(),
    ]);

    $response = $this->actingAs($user)->post(route('reservations.store'), [
        'table_id' => $table->id,
        'game_id' => $game->id,
        'date' => '2026-05-10',
        'start_time' => '14:00:00',
        'end_time' => '16:00:00',
        'spots' => 4,
    ]);

    $response->assertSessionHasNoErrors();
});