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

                @if($game->review_count > 0)
                <div class="mt-8 card-surface p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-950">Rating</h3>
                            <div class="flex items-center gap-2 mt-2">
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= round($game->average_rating) ? 'text-amber-400' : 'text-slate-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-lg font-semibold text-slate-900">{{ $game->average_rating }}</span>
                                <span class="text-sm text-slate-500">({{ $game->review_count }} reviews)</span>
                            </div>
                        </div>
                        <a href="{{ route('games.reviews', $game) }}" class="btn-secondary">View Reviews</a>
                    </div>
                </div>
                @endif
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
