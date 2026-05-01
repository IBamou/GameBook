<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Http\Requests\GameStoreRequest;
use App\Http\Requests\GameUpdateRequest;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $games = Game::all();

        return view('games.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('games.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GameStoreRequest $request)
    {
        $data = $request->validated();

        Game::create($data);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        return view('games.show', compact('game'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        return view('games.edit', compact('game'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GameUpdateRequest $request, Game $game)
    {
        $game->update($request->validated());

        return redirect()->route('games.show', $game);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        $game->delete();

        return back();
    }
}
