@extends('layouts.app')

@section('content')
    <div class="py-10 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-slate-950">{{ $category->name }}</h1>
                </div>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <div class="flex gap-2">
                            <a href="{{ route('games.create') }}?cat={{ $category->id }}" class="btn-secondary">Add Game</a>
                            <a href="{{ route('categories.edit', $category) }}" class="btn-secondary">Edit</a>
                            <form action="{{ route('categories.delete', $category) }}" method="POST"
                                onsubmit="return confirm('Delete this category?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-secondary text-red-600 hover:bg-red-50">Delete</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>

            <div class="card-surface p-6 mb-8">
                <p class="text-slate-700 leading-relaxed text-lg">{{ $category->description }}</p>
            </div>

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-slate-950 mb-6">Games in {{ $category->name }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($category->games as $game)
                        <a href="{{ route('games.show', $game) }}" class="card-hover group overflow-hidden">
                            <div
                                class="aspect-video bg-gradient-to-br from-sky-100 to-slate-100 flex items-center justify-center">
                                @if($game->image_url)
                                    <img src="{{ $game->image_url }}" alt="{{ $game->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4" />
                                    </svg>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-slate-950 group-hover:text-sky-600 transition-colors">
                                    {{ $game->name }}</h3>
                                <p class="text-xs text-slate-500 mt-1">{{ $game->difficulty }} •
                                    {{ $game->min_players }}–{{ $game->max_players }} players</p>
                                <p class="text-sm font-semibold text-sky-600 mt-2">{{ number_format($game->price, 2) }} MAD</p>
                            </div>
                        </a>
                    @empty
                        <div class="empty-state col-span-full py-12">
                            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4" />
                            </svg>
                            <p class="empty-state-text">No games in this category yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
