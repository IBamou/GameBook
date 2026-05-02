@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header">
            <div class="section-title">
                <h1>My Sessions</h1>
                <p>Your session history</p>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($sessions as $reservation)
                @if($reservation->sessions->count() > 0)
                    @foreach($reservation->sessions as $session)
                        @php
                            $isActive = $session->status === 'active' && $session->started_at;
                            $remainingSeconds = 0;
                            $endTime = null;
                            
                            if ($isActive) {
                                $startedAt = \Carbon\Carbon::parse($session->started_at, 'Africa/Casablanca');
                                $endTime = $startedAt->clone()->addMinutes($session->duration);
                                $remainingSeconds = max(0, (int) now('Africa/Casablanca')->diffInSeconds($endTime, false));
                            }
                        @endphp
                        <div class="card-surface p-5">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="font-semibold text-slate-900">Table {{ $reservation->table->reference }}</p>
                                    <p class="text-sm text-slate-500 mt-1">
                                        {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }} • 
                                        {{ \Carbon\Carbon::parse($session->started_at ?? $reservation->start_time)->format('H:i') }}
                                        @if($session->duration)
                                            <span class="ml-2">({{ $session->duration }} min)</span>
                                        @endif
                                    </p>
                                </div>
                                <span class="
                                    {{ $session->status === 'inactive' ? 'badge-ready' : '' }}
                                    {{ $session->status === 'active' ? 'badge-in-progress' : '' }}
                                    {{ $session->status === 'ended' ? 'badge-completed' : '' }}
                                ">
                                    {{ $session->status }}
                                </span>
                            </div>

                            @if($isActive)
                                @php
                                    $currentGame = $session->currentGame ?? $reservation->game;
                                @endphp
                                @if($currentGame)
                                    <div class="flex items-center justify-between text-sm mb-3">
                                        <span class="text-slate-500">Playing:</span>
                                        <span class="font-semibold text-slate-900">{{ $currentGame->name }}</span>
                                    </div>
                                @endif
                                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-lg p-4 text-center">
                                    <div id="countdown-user-{{ $session->id }}"
                                        class="text-3xl font-bold text-white countdown-timer"
                                        data-end-time="{{ $endTime->timestamp }}"
                                        data-remaining="{{ $remainingSeconds }}">
                                        {{ floor($remainingSeconds / 60) }}:{{ str_pad($remainingSeconds % 60, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <p class="text-xs text-white/80 mt-1">Time remaining</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            @empty
                <div class="empty-state">
                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="empty-state-text">No sessions yet</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script type="module">
    setInterval(function() {
        document.querySelectorAll('.countdown-timer').forEach(function(el) {
            var remainingSeconds = parseInt(el.getAttribute('data-remaining'));
            
            if (isNaN(remainingSeconds) || remainingSeconds < 0) return;
            
            if (remainingSeconds === 0) {
                location.reload();
                return;
            }
            
            remainingSeconds--;
            el.setAttribute('data-remaining', remainingSeconds);
            
            var mins = Math.floor(remainingSeconds / 60);
            var secs = remainingSeconds % 60;
            
            el.textContent = (mins < 10 ? '0' + mins : mins) + ':' + (secs < 10 ? '0' + secs : secs);
        });
    }, 1000);
</script>
@endpush
@endsection