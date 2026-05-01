@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header">
            <div class="section-title">
                <h1>My Reservations</h1>
                <p>Your game table booking history</p>
            </div>
            <a href="{{ route('reservations.create') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Reservation
            </a>
        </div>

        <div class="space-y-3">
            @forelse($reservations as $reservation)
                <a href="{{ route('reservations.show', $reservation) }}" class="group card-surface p-5 flex items-center justify-between hover:border-sky-300 hover:shadow-lg transition-all">
                    <div class="flex items-center gap-6 flex-1 min-w-0">
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-950 group-hover:text-sky-600 transition-colors">Table {{ $reservation->table->reference }}</h3>
                            <p class="text-sm text-slate-500 mt-1">{{ \Carbon\Carbon::parse($reservation->date)->format('d M Y') }}</p>
                        </div>
                        <div class="hidden sm:block px-4 py-2 bg-slate-50 rounded-lg border border-slate-200">
                            <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Time</p>
                            <p class="font-semibold text-slate-900">
                                {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                            </p>
                        </div>
                        @if($reservation->game)
                            <div class="hidden lg:block px-4 py-2 bg-slate-50 rounded-lg border border-slate-200">
                                <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Game</p>
                                <p class="font-semibold text-slate-900">{{ $reservation->game->name }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-4 ml-4 flex-shrink-0">
                        <span class="
                            {{ $reservation->status === 'confirmed' ? 'badge-confirmed' : '' }}
                            {{ $reservation->status === 'pending' ? 'badge-pending' : '' }}
                            {{ $reservation->status === 'cancelled' ? 'badge-cancelled' : '' }}
                            {{ $reservation->status === 'completed' ? 'badge-completed' : '' }}
                        ">
                            {{ ucfirst($reservation->status) }}
                        </span>
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-sky-500 group-hover:translate-x-1 transition-all">
                            <path fill="currentColor" d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L12.17 11H4v2h8.17l-3.58 3.59z"/>
                        </svg>
                    </div>
                </a>
            @empty
                <div class="empty-state py-20">
                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="empty-state-text mb-6">No reservations yet</p>
                    <a href="{{ route('reservations.create') }}" class="btn-primary">Make Your First Reservation</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
