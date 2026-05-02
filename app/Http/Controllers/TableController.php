<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Http\Requests\TableStoreRequest;
use App\Http\Requests\TableUpdateRequest;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Table::with('todayReservations')->get();

        return view('tables.index', compact('tables'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tables.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TableStoreRequest $request)
    {
        Table::create($request->validated());

        return redirect()->route('tables.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Table $table)
    {
        $table->load('todayReservations');

        return view('tables.show', compact('table'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Table $table)
    {
        return view('tables.edit', compact('table'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TableUpdateRequest $request, Table $table)
    {
        $table->update($request->validated());

        return redirect()->route('tables.show', $table);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        $table->delete();

        return redirect()->route('tables.index');
    }
}
