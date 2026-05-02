<?php

namespace App\Http\Controllers;

use App\Events\TableStatusChanged;
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

    public function mySessions()
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
        
        $now = now();
        $reservedStart = \Carbon\Carbon::parse($reservation->date . ' ' . $reservation->start_time);
        $originalDuration = $session->duration;
        
        // Calculate how many minutes late (if any)
        if ($now->greaterThan($reservedStart)) {
            $lateMinutes = (int) abs($now->diffInMinutes($reservedStart, false));
            $newDuration = max(1, $originalDuration - $lateMinutes); // At least 1 min
        } else {
            $newDuration = $originalDuration;
        }

        $session->update([
            'status' => 'active',
            'started_at' => $now,
            'duration' => $newDuration,
            'current_game_id' => $reservation->game_id,
        ]);

        event(new TableStatusChanged(
            $reservation->table_id,
            'in_progress',
            [
                'session_id' => $session->id,
                'duration' => $newDuration,
                'reservation_id' => $reservation->id,
            ]
        ));

        return back();
    }

    public function end(Reservation $reservation)
    {
        $session = $reservation->sessions()->where('status', 'active')->firstOrFail();
        $currentGameId = $session->current_game_id;

        $session->update([
            'status' => 'ended',
            'ended_at' => now(),
            'current_game_id' => null,
        ]);

        // Make the game available again
        if ($currentGameId) {
            \App\Models\Game::where('id', $currentGameId)->update(['status' => 'available']);
        }

        // Cancel the reservation - user left early
        $reservation->update(['status' => 'cancelled']);

        event(new TableStatusChanged(
            $reservation->table_id,
            'available',
            [
                'session_id' => $session->id,
                'reservation_id' => $reservation->id,
                'reason' => 'user_left_early',
            ]
        ));

        return back();
    }

    public function updateGame(Request $request, Reservation $reservation)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id'
        ]);

        $session = $reservation->sessions()->where('status', 'active')->firstOrFail();
        $newGame = Game::findOrFail($request->game_id);
        $oldGameId = $session->current_game_id;

        // 1. Validation: Is the new game available? (Check overlapping sessions)
        $startedAt = \Carbon\Carbon::parse($session->started_at);
        $sessionEnd = $startedAt->copy()->addMinutes($session->duration);
        
        $conflict = \App\Models\ReservationSession::where('id', '!=', $session->id)
            ->where('current_game_id', $newGame->id)
            ->whereNotIn('status', ['ended', 'cancelled'])
            ->whereHas('reservation', function ($q) use ($reservation, $sessionEnd, $startedAt) {
                $q->where('date', $reservation->date)
                  ->where('start_time', '<', $sessionEnd->format('H:i:s'))
                  ->where('end_time', '>', $startedAt->format('H:i:s'));
            })
            ->exists();

        if ($conflict) {
            return back()->with('error', 'This game is already reserved for another session during this time.');
        }

        // 2. Pricing: High-Water Mark Logic
        $originalPrice = (float) $reservation->price;
        $priceDifference = (float) $newGame->price - $originalPrice;

        $additionalCharges = $session->additional_charges ?? 0;
        
        // If switching to more expensive game and difference > current extra charges, update
        if ($priceDifference > 0 && $priceDifference > $additionalCharges) {
            $additionalCharges = $priceDifference;
        }

        // 3. Update the session
        $session->update([
            'current_game_id' => $newGame->id,
            'additional_charges' => $additionalCharges,
        ]);

        // 4. Update game statuses (old game = available, new game = busy)
        if ($oldGameId && $oldGameId !== $newGame->id) {
            \App\Models\Game::where('id', $oldGameId)->update(['status' => 'available']);
        }
        $newGame->update(['status' => 'busy']);

        // 5. Fire event for real-time update
        event(new TableStatusChanged(
            $reservation->table_id,
            'in_progress',
            [
                'session_id' => $session->id,
                'game_name' => $newGame->name,
                'additional_charges' => $additionalCharges,
            ]
        ));

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Switched to {$newGame->name} successfully.",
                'game' => $newGame->name,
            ]);
        }
        
        return back()->with('success', "Switched to {$newGame->name} successfully.");
    }

    public function availableGames(Request $request, Reservation $reservation)
    {
        $session = $reservation->sessions()->where('status', 'active')->firstOrFail();
        $currentPrice = (float) ($reservation->game->price ?? 0);
        
        $startedAt = \Carbon\Carbon::parse($session->started_at);
        $sessionEnd = $startedAt->copy()->addMinutes($session->duration);
        
        $games = \App\Models\Game::whereIn('status', ['available', 'busy'])
            ->get()
            ->map(function ($game) use ($session, $sessionEnd, $startedAt, $reservation, $currentPrice) {
                $conflict = \App\Models\ReservationSession::where('id', '!=', $session->id)
                    ->where('current_game_id', $game->id)
                    ->whereNotIn('status', ['ended', 'cancelled'])
                    ->whereHas('reservation', function ($q) use ($reservation, $sessionEnd, $startedAt) {
                        $q->where('date', $reservation->date)
                          ->where('start_time', '<', $sessionEnd->format('H:i:s'))
                          ->where('end_time', '>', $startedAt->format('H:i:s'));
                    })
                    ->exists();
                
                $priceDiff = (float) $game->price - $currentPrice;
                
                return [
                    'id' => $game->id,
                    'name' => $game->name,
                    'price' => $game->price,
                    'image' => $game->image,
                    'available' => !$conflict,
                    'current' => $session->current_game_id == $game->id,
                    'price_difference' => $priceDiff,
                ];
            });
        
        return response()->json([
            'current_game' => $session->currentGame?->name,
            'current_price' => $currentPrice,
            'games' => $games,
        ]);
    }
}
