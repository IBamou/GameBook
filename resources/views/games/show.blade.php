@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-4xl font-bold text-slate-950">{{ $game->name }}</h1>
                <p class="mt-2 text-slate-600">{{ $game->category->name ?? 'Uncategorized' }}</p>
            </div>
            @auth
                @if(auth()->user()->role === 'admin')
                <div class="flex gap-2">
                    <a href="" class="btn-secondary">Edit</a>
                    <form action="" method="POST" onsubmit="return confirm('Delete this game?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-secondary text-red-600 hover:bg-red-50">Delete</button>
                    </form>
                </div>
                @endif
            @endauth
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="card-surface overflow-hidden">
                    @if($game->image_url)
                        <img src="{{ $game->image_url }}" alt="{{ $game->name }}" class="w-full h-96 object-cover">
                    @else
                        <div class="w-full h-96 bg-gradient-to-br from-sky-100 to-slate-100 flex items-center justify-center">
                            <svg class="w-24 h-24 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-slate-950 mb-4">About this game</h2>
                    <p class="text-slate-700 leading-relaxed text-lg">{{ $game->description }}</p>
                </div>
            </div>

            <div class="card-surface p-6 h-fit">
                <div class="space-y-6">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Category</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $game->category->name ?? 'Uncategorized' }}</p>
                    </div>

                    <div class="border-t border-slate-200 pt-6">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Difficulty Level</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900 capitalize">{{ $game->difficulty }}</p>
                    </div>

                    <div class="border-t border-slate-200 pt-6">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Players</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $game->min_players }}–{{ $game->max_players }}</p>
                    </div>

                    <div class="border-t border-slate-200 pt-6">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Game Duration</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $game->duration }} minutes</p>
                    </div>

                    <div class="border-t border-slate-200 pt-6">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Price</p>
                        <p class="mt-2 text-3xl font-bold text-sky-600">{{ number_format($game->price, 2) }} MAD</p>
                    </div>

                    <div class="border-t border-slate-200 pt-6">
                        <span class="
                            {{ $game->status === 'available' ? 'badge-available' : 'badge-available' }}
                        ">
                            {{ ucfirst($game->status) }}
                        </span>
                    </div>
                    <a href="{{ route('reservations.create') }}?game={{ $game->id }}" class="btn-primary w-full">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Reserve Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
