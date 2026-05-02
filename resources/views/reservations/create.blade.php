@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-950">Create Reservation</h1>
            <p class="mt-2 text-slate-600">Book a game table for your next gaming session</p>
        </div>

        <div class="card-surface p-8">
            <form action="{{ route('reservations.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="form-group">
                    <label class="form-label">Table *</label>
                    <select name="table_id" class="form-input" required>
                        <option value="">Select a table</option>
                        @foreach(\App\Models\Table::all() as $table)
                            <option value="{{ $table->id }}" @selected(old('table_id') == $table->id || request('tab') == $table->id)>
                                {{ $table->reference }} — {{ $table->capacity }} seats
                            </option>
                        @endforeach
                    </select>
                    @error('table_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Game (optional)</label>
                    <select name="game_id" class="form-input">
                        <option value="">Choose a game</option>
                        @foreach(\App\Models\Game::where('status', 'available')->get() as $game)
                            <option value="{{ $game->id }}"  @selected((request('game') !== null ?  request('game') == $game->id : old('game_id') == $game->id ))>
                                {{ $game->name }} — {{ number_format($game->price, 2) }} MAD
                            </option>
                        @endforeach
                    </select>
                    @error('game_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Date *</label>
                        <input type="date" name="date" class="form-input" value="{{ old('date') }}" required>
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Number of Spots *</label>
                        <input type="number" name="spots" class="form-input" value="{{ old('spots', 1) }}" min="1" required>
                        @error('spots')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Start Time *</label>
                        <input type="time" name="start_time" class="form-input" value="{{ old('start_time') }}" required>
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Time *</label>
                        <input type="time" name="end_time" class="form-input" value="{{ old('end_time') }}" required>
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="btn-primary flex-1">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Create Reservation
                    </button>
                    <a href="{{ route('reservations.my') }}" class="btn-secondary flex-1">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
