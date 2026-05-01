<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ReservationSession;
use App\Models\Table;
use App\Models\Game;
use Illuminate\Http\Request;

class ReservationSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Table::with(['todayReservations' => function ($query) {
            $query->whereDate('date', today())
                ->where('status', 'confirmed')
                ->orderBy('start_time');
        }])->get();
        $today = today();

        return view('sessions.index', compact('tables', 'today'));
    }

    public function my()
    {
        $sessions = auth()->user()
            ->reservations()
            ->whereDate('date', today())
            ->whereHas('sessions')
            ->with('sessions')
            ->get();

        return view('sessions.my', compact('sessions'));
    }

    public function start(Reservation $reservation)
    {
        $session = $reservation->sessions()->where('status', 'inactive')->firstOrFail();

        $session->update([
            'status' => 'active',
            'started_at' => now(),
        ]);

        return back();
    }

    public function end(Reservation $reservation)
    {
        $session = $reservation->sessions()->where('status', 'active')->firstOrFail();

        $session->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);

        return back();
    }

    public function updateGame(Request $request, Reservation $reservation)
    {
        $session = $reservation->sessions()->where('status', 'active')->firstOrFail();

        $session->sessionGames()->where('is_active', true)->update(['is_active' => false]);

        $game = Game::findOrFail($request->game_id);

        $session->sessionGames()->create([
            'game_id' => $game->id,
            'price_at_time' => $game->price,
            'is_active' => true,
        ]);

        return back();
    }
}
