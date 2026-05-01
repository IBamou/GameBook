<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationStatusRequest;
use App\Http\Requests\ReservationStoreRequest;
use App\Http\Requests\ReservationUpdateRequest;
use App\Models\Reservation;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::all();

        return view('reservations.index', compact('reservations'));
    }

/**
     * Update Reservation Status
     */
    public function status(ReservationStatusRequest $request, Reservation $reservation)
    {
        $newStatus = $request->validated()['status'];
        $reservation->update(['status' => $newStatus]);
        $reservation->createSessionIfConfirmed();
        $reservation->cancelSessionIfCancelled();

        return back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationStoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        Reservation::create($data);

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
        $reservation->delete();

        return redirect()->route('reservations.my');
    }

    public function my()
    {
        $reservations = auth()->user()->reservations;

        return view('reservations.my', compact('reservations'));
    }
}
