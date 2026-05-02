@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header">
            <div class="section-title">
                <h1>Session Management</h1>
                <p>{{ \Carbon\Carbon::parse($today)->format('l, F j, Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse($tables as $table)
                @php
                    $table->load('reservations.game', 'reservations.sessions', 'reservations.user');
                    $todayRes = $table->todayReservations->where('status', 'confirmed');
                    $currentReservation = null;
                    $currentSession = null;
                    
                    foreach ($todayRes as $res) {
                        if ($res->sessions->where('status', 'active')->isNotEmpty()) {
                            $currentSession = $res->sessions->where('status', 'active')->first();
                            $currentReservation = $res;
                            break;
                        }
                    }
                    
                    if (!$currentReservation) {
                        $currentReservation = $todayRes->first();
                    }
                    
                    $status = $table->status;
                @endphp

                <div class="card-surface overflow-hidden transition-all duration-200 hover:scale-[1.01] hover:shadow-xl
                    {{ $status === 'in_progress' ? 'ring-2 ring-emerald-500 border-emerald-500' : '' }}
                    {{ $status === 'ready' ? 'ring-2 ring-amber-500 border-amber-500' : '' }}">
                    
                    <!-- Card Header -->
                    <div class="bg-slate-900 px-4 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011 1v6a1 1 0 01-1 1h-1a1 1 0 01-1-1v-6z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white">Table {{ $table->reference }}</h3>
                                <p class="text-xs text-white/60">{{ $table->capacity }} seats</p>
                            </div>
                        </div>
                        <span class="
                            {{ $status === 'available' ? 'badge-available' : '' }}
                            {{ $status === 'booked' ? 'badge-booked' : '' }}
                            {{ $status === 'ready' ? 'badge-ready' : '' }}
                            {{ $status === 'in_progress' ? 'badge-in-progress' : '' }}
                        ">
                            {{ ucfirst($status) }}
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4">
                        @switch($status)
                            @case('available')
                                <div class="py-6 text-center">
                                    <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-slate-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                    <p class="text-slate-600 font-medium">This table is available</p>
                                    <p class="text-sm text-slate-400 mt-1">Ready for reservation</p>
                                    <a href="{{ route('reservations.create') }}?tab={{ $table->id }}" class="mt-4 btn-primary inline-flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Reserve Now
                                    </a>
                                </div>
                                @break

                            @case('booked')
                            @case('ready')
                                @if($currentReservation)
                                    <div class="space-y-3">
                                        <!-- Time Slot -->
                                        <div class="flex items-center justify-between py-2 border-b border-slate-100">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                </svg>
                                                <span class="text-sm text-slate-600">Time</span>
                                            </div>
                                            <span class="font-semibold text-slate-900">
                                                {{ \Carbon\Carbon::parse($currentReservation->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($currentReservation->end_time)->format('H:i') }}
                                            </span>
                                        </div>
                                        
                                        <!-- Spots -->
                                        <div class="flex items-center justify-between py-2 border-b border-slate-100">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                <span class="text-sm text-slate-600">Players</span>
                                            </div>
                                            <span class="font-semibold text-slate-900">{{ $currentReservation->spots }}</span>
                                        </div>
                                        
                                        @if($currentReservation->game)
                                        <!-- Game -->
                                        <div class="py-2 border-b border-slate-100">
                                            <div class="flex items-center gap-2 mb-1">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4"/>
                                                </svg>
                                                <span class="text-sm text-slate-600">Game</span>
                                            </div>
                                            <p class="font-semibold text-slate-900">{{ $currentReservation->game->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $currentReservation->game->duration }} min • {{ $currentReservation->game->price }} MAD</p>
                                        </div>
                                        @endif
                                        
                                        <!-- Customer -->
                                        <div class="py-2">
                                            <div class="flex items-center gap-2 mb-1">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                <span class="text-sm text-slate-600">Reserved by</span>
                                            </div>
                                            <p class="font-semibold text-slate-900">{{ $currentReservation->user->name }}</p>
                                        </div>

                                        @if($status === 'ready')
                                        <!-- Start Button -->
                                        <form action="{{ route('sessions.start', $currentReservation) }}" method="POST" class="mt-4">
                                            @csrf
                                            <button type="submit" class="btn-primary w-full flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.128A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.128a1 1 0 000-1.664z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Start Session
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                @endif
                                @break

                            @case('in_progress')
                                @if($currentSession && $currentReservation)
                                    @php
                                        $startedAt = \Carbon\Carbon::parse($currentSession->started_at);
                                        $remainingSeconds = ($currentSession->duration * 60) - $startedAt->diffInSeconds(now());
                                        if ($remainingSeconds < 0) $remainingSeconds = 0;
                                    @endphp
                                    <div class="space-y-4">
                                        <!-- Countdown Timer -->
                                        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg p-4 text-center">
                                            <div id="countdown-{{ $table->id }}" class="text-4xl font-bold text-white" data-duration="{{ $remainingSeconds }}">
                                                {{ gmdate('i:s', $remainingSeconds) }}
                                            </div>
                                            <p class="text-xs text-white/80 mt-1">time remaining</p>
                                        </div>
                                        
                                        <!-- Current Game -->
                                        <div class="py-2">
                                            <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">Playing</span>
                                            <p class="font-bold text-slate-900 text-lg">{{ $currentReservation->game->name ?? 'No game selected' }}</p>
                                        </div>
                                        
                                        <!-- Player -->
                                        <div class="py-2">
                                            <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">Customer</span>
                                            <p class="font-semibold text-slate-900">{{ $currentReservation->user->name }}</p>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex gap-2 mt-4">
                                            <button class="btn-sm-secondary flex-1">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m2-2l2.828-2.828a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                Game
                                            </button>
                                            <form action="{{ route('sessions.end', $currentReservation) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit" class="btn-sm-danger w-full flex items-center justify-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                                                    </svg>
                                                    End
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                                @break
                        @endswitch
                    </div>
                </div>
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

@push('scripts')
<script>
    document.querySelectorAll('[id^="countdown-"]').forEach(function(el) {
        let duration = parseInt(el.dataset.duration);
        
        setInterval(function() {
            if (duration > 0) {
                duration--;
                let minutes = Math.floor(duration / 60);
                let seconds = duration % 60;
                el.textContent = 
                    (minutes < 10 ? '0' + minutes : minutes) + ':' + 
                    (seconds < 10 ? '0' + seconds : seconds);
            }
        }, 1000);
    });
</script>
@endpush
@endsection