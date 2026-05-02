<?php

use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use App\Models\Game;

it('prevents overlapping reservations on the same table', function () {
    $user = User::factory()->create();
    $table = Table::factory()->create();
    $game = Game::factory()->create();

    Reservation::create([
        'user_id' => $user->id,
        'table_id' => $table->id,
        'game_id' => $game->id,
        'date' => '2026-05-10',
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
        'spots' => 4,
        'status' => 'confirmed',
        'price' => 100,
    ]);

    $response = $this->actingAs($user)->post(route('reservations.store'), [
        'table_id' => $table->id,
        'game_id' => $game->id,
        'date' => '2026-05-10',
        'start_time' => '11:00:00',
        'end_time' => '13:00:00',
        'spots' => 4,
    ]);

    $response->assertSessionHasErrors('time');
    expect(session('errors')->first('time'))->toContain('already reserved');
});

it('allows non-overlapping reservations on the same table', function () {
    $user = User::factory()->create();
    $table = Table::factory()->create();
    $game = Game::factory()->create();

    Reservation::create([
        'user_id' => $user->id,
        'table_id' => $table->id,
        'game_id' => $game->id,
        'date' => '2026-05-10',
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
        'spots' => 4,
        'status' => 'confirmed',
        'price' => 100,
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

it('allows reservations on different tables same time', function () {
    $user = User::factory()->create();
    $table1 = Table::factory()->create();
    $table2 = Table::factory()->create();
    $game = Game::factory()->create();

    Reservation::create([
        'user_id' => $user->id,
        'table_id' => $table1->id,
        'game_id' => $game->id,
        'date' => '2026-05-10',
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
        'spots' => 4,
        'status' => 'confirmed',
        'price' => 100,
    ]);

    $response = $this->actingAs($user)->post(route('reservations.store'), [
        'table_id' => $table2->id,
        'game_id' => $game->id,
        'date' => '2026-05-10',
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
        'spots' => 4,
    ]);

    $response->assertSessionHasNoErrors();
});

it('allows reservations on different dates same time', function () {
    $user = User::factory()->create();
    $table = Table::factory()->create();
    $game = Game::factory()->create();

    Reservation::create([
        'user_id' => $user->id,
        'table_id' => $table->id,
        'game_id' => $game->id,
        'date' => '2026-05-10',
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
        'spots' => 4,
        'status' => 'confirmed',
        'price' => 100,
    ]);

    $response = $this->actingAs($user)->post(route('reservations.store'), [
        'table_id' => $table->id,
        'game_id' => $game->id,
        'date' => '2026-05-11',
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
        'spots' => 4,
    ]);

    $response->assertSessionHasNoErrors();
});