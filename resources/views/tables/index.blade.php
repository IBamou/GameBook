@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header">
            <div class="section-title">
                <h1>Tables</h1>
                <p>Manage your game tables and seating</p>
            </div>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('tables.create') }}" class="btn-primary">Add Table</a>
                @endif
            @endauth
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            @forelse($tables as $table)
                <a href="{{ route('tables.show', $table) }}" class="card-hover group flex flex-col items-center p-4 text-center">
                    <div class="text-3xl font-bold text-sky-600 group-hover:scale-110 transition-transform">{{ $table->reference }}</div>
                    <p class="mt-2 text-sm text-slate-600">{{ $table->capacity }} seats</p>
                    <div class="mt-3 text-xs text-slate-400 group-hover:text-sky-600 transition-colors flex items-center gap-1">
                        <span>View</span>
                        <span class="group-hover:translate-x-1 transition-transform">→</span>
                    </div>
                </a>
            @empty
                <div class="empty-state col-span-full">
                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <p class="empty-state-text">No tables available</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
