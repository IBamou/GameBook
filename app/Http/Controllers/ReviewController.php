<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Reservation;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'game_id' => 'required|exists:games,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500',
        ]);

        $reservation = Reservation::findOrFail($request->reservation_id);
        
        if ($reservation->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized');
        }

        if ($reservation->status !== 'completed') {
            return back()->with('error', 'Can only review completed reservations');
        }

        $existingReview = Review::where('user_id', auth()->id())
            ->where('reservation_id', $request->reservation_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this reservation');
        }

        Review::create([
            'user_id' => auth()->id(),
            'game_id' => $request->game_id,
            'reservation_id' => $request->reservation_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Thank you for your review!');
    }

    public function gameReviews(Game $game)
    {
        $reviews = $game->reviews()->with('user')->latest()->paginate(10);
        
        return view('reviews.game', compact('game', 'reviews'));
    }
}