@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header">
            <div class="section-title">
                <h1>Games</h1>
                <p>Browse and manage our collection of board games</p>
            </div>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('games.create') }}" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Game
                    </a>
                @endif
            @endauth
        </div>

        <form method="GET" class="mb-6 flex flex-wrap gap-3 items-center">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search games..." 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            </div>
            <select name="category" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                <option value="">All Categories</option>
                @foreach(\App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="status" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                <option value="">All Status</option>
                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
            @if(request('search') || request('category') || request('status'))
                <a href="{{ route('games.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Clear</a>
            @endif
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($games as $game)
                <a href="{{ route('games.show', $game) }}" class="card-hover group overflow-hidden flex flex-col">
                    <div class="aspect-video bg-gradient-to-br from-sky-100 to-slate-100 flex items-center justify-center overflow-hidden">
                        @if($game->image_url)
                            <img src="{{ $game->image_url }}" alt="{{ $game->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1 p-4 flex flex-col">
                        <div class="flex items-start justify-between mb-2 gap-2">
                            <h3 class="font-semibold text-slate-950 group-hover:text-sky-600 transition-colors line-clamp-2">{{ $game->name }}</h3>
                            <span class="badge-available">{{ $game->status }}</span>
                        </div>
                        <p class="text-xs text-slate-500 mb-3">{{ $game->category->name ?? 'Uncategorized' }}</p>
                        <div class="mt-auto pt-3 border-t border-slate-100">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-600">{{ $game->min_players }}-{{ $game->max_players }} players</span>
                                <span class="font-semibold text-sky-600">{{ number_format($game->price, 2) }} MAD</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="empty-state col-span-full">
                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4"/>
                    </svg>
                    <p class="empty-state-text">No games available yet</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $games->links() }}
        </div>
    </div>
</div>
@endsection
