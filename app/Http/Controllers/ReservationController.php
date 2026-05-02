<?php

namespace App\Http\Controllers;

use App\Events\TableStatusChanged;
use App\Http\Requests\ReservationStatusRequest;
use App\Http\Requests\ReservationStoreRequest;
use App\Http\Requests\ReservationUpdateRequest;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $date = $request->get('date');
        $table = $request->get('table');

        $reservations = Reservation::with(['user', 'table', 'game'])
            ->when($search, fn($q) => $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($date, fn($q) => $q->whereDate('date', $date))
            ->when($table, fn($q) => $q->where('table_id', $table))
            ->orderBy('date', 'desc')
            ->orderBy('start_time')
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Update Reservation Status
     */
    public function status(ReservationStatusRequest $request, Reservation $reservation)
    {
        $oldStatus = $reservation->status;
        $newStatus = $request->validated()['status'];
        $reservation->update(['status' => $newStatus]);
        $reservation->createSessionIfConfirmed();
        $reservation->cancelSessionIfCancelled();

        if ($oldStatus !== $newStatus) {
            $status = match ($newStatus) {
                'confirmed' => 'booked',
                'cancelled' => 'available',
                default => $newStatus,
            };

            event(new TableStatusChanged(
                $reservation->table_id,
                $status,
                ['reservation_id' => $reservation->id]
            ));
        }

        return back();
    }

    /**
     * Create new Reservation
     */
    public function create()
    {
        return view('reservations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationStoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        
        // Check auto-confirm setting
        $autoConfirm = \App\Models\Setting::where('key', 'auto_confirm_reservation')->value('value') ?? '0';
        if ($autoConfirm === '1') {
            $data['status'] = 'confirmed';
        }

        $reservation = Reservation::create($data);

        if ($reservation->status === 'confirmed') {
            event(new TableStatusChanged(
                $reservation->table_id,
                'booked',
                [
                    'reservation_id' => $reservation->id,
                    'starts_at' => $reservation->start_time,
                    'ends_at' => $reservation->end_time,
                ]
            ));
        }

        return redirect()->route('reservations.my');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        return view('reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        return view('reservations.edit', compact('reservation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReservationUpdateRequest $request, Reservation $reservation)
    {
        $reservation->update($request->validated());

        return redirect()->route('reservations.show', $reservation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $tableId = $reservation->table_id;
        $reservation->delete();

        event(new TableStatusChanged(
            $tableId,
            'available',
            ['reservation_id' => null]
        ));

        return redirect()->route('reservations.my');
    }

    public function my()
    {
        $reservations = auth()->user()->reservations;

        return view('reservations.my', compact('reservations'));
    }

    public function calendar()
    {
        $month = request('month', now()->month);
        $year = request('year', now()->year);

        $startOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $reservations = Reservation::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->whereNotIn('status', ['cancelled'])
            ->with(['user', 'table', 'game'])
            ->get();

        return view('reservations.calendar', compact('reservations', 'month', 'year', 'startOfMonth'));
    }
}
