<?php

namespace App\Http\Controllers;

use App\Mail\WaitlistAvailable;
use App\Models\Reservation;
use App\Models\Waitlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WaitlistController extends Controller
{
    public function index()
    {
        $waitlist = Waitlist::with(['user', 'table'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('waitlist.index', compact('waitlist'));
    }

    public function my()
    {
        $waitlist = auth()->user()->waitlist()
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('waitlist.my', compact('waitlist'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $conflict = Waitlist::where('table_id', $request->table_id)
            ->where('date', $request->date)
            ->where('status', 'waiting')
            ->where(function ($q) use ($request) {
                $q->whereTime('start_time', '<', $request->end_time)
                  ->whereTime('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($conflict) {
            return back()->with('error', 'You are already on the waitlist for this time slot.');
        }

        Waitlist::create([
            'user_id' => auth()->id(),
            'table_id' => $request->table_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return back()->with('success', 'Added to waitlist! We\'ll notify you if a spot opens.');
    }

    public function destroy(Waitlist $waitlist)
    {
        $this->authorize('delete', $waitlist);
        
        $waitlist->delete();

        return back()->with('success', 'Removed from waitlist.');
    }

    public function checkAndNotify()
    {
        $waiting = Waitlist::where('status', 'waiting')->get();
        $today = now()->toDateString();

        foreach ($waiting as $item) {
            $hasReservation = Reservation::where('table_id', $item->table_id)
                ->where('date', $item->date)
                ->whereNotIn('status', ['cancelled'])
                ->whereTime('start_time', '<', $item->end_time)
                ->whereTime('end_time', '>', $item->start_time)
                ->exists();

            if (!$hasReservation) {
                $item->update(['status' => 'notified']);
                
                Mail::to($item->user->email)->send(new WaitlistAvailable($item));
            } elseif ($item->date < $today) {
                $item->update(['status' => 'expired']);
            }
        }

        $this->info('Waitlist check completed.');
    }
}