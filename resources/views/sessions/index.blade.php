@extends('layouts.app')

@section('content')
<div x-data="gameSwitcher()" class="py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header">
            <div class="section-title">
                <h1>Session Management</h1>
                <p>{{ \Carbon\Carbon::parse($today)->format('l, F j, Y') }}</p>
            </div>
        </div>

        <!-- Toast -->
        <div x-show="toast" x-transition class="fixed top-5 right-5 z-50 bg-emerald-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span x-text="toastMessage"></span>
        </div>
        <div x-show="toastError" x-transition class="fixed top-5 right-5 z-50 bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span x-text="toastError"></span>
        </div>

        <!-- Game Switch Modal -->
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="modalOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="modalOpen = false"></div>
            <div x-show="modalOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-900">Switch Game</h2>
                        <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="mt-3 p-3 bg-slate-50 rounded-lg flex items-center justify-between text-sm">
                        <span class="text-slate-600">Current:</span>
                        <span class="font-semibold text-slate-900" x-text="currentGameName"></span>
                        <span class="text-slate-400" x-text="currentPrice ? currentPrice + ' MAD' : ''"></span>
                    </div>
                </div>
                <div class="p-5 max-h-80 overflow-y-auto">
                    <div class="grid grid-cols-2 gap-3">
                        <template x-for="game in games" :key="game.id">
                            <div class="border-2 rounded-xl p-3 cursor-pointer transition-all"
                                :class="selectedGame?.id === game.id ? 'border-sky-500 bg-sky-50' : (game.available ? 'border-slate-200 hover:border-slate-300' : 'border-slate-100 opacity-50')"
                                @click="game.available ? selectGame(game) : null">
                                <div class="h-16 bg-slate-100 rounded-lg mb-2 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4"/></svg>
                                </div>
                                <p class="font-semibold text-slate-900 text-sm" x-text="game.name"></p>
                                <p class="text-xs text-slate-500" x-text="game.price + ' MAD'"></p>
                                <p x-show="!game.available" class="text-xs text-red-500 mt-1">Unavailable</p>
                            </div>
                        </template>
                    </div>
                </div>
                <div x-show="selectedGame" x-transition class="p-5 border-t border-slate-100 bg-slate-50">
                    <div class="flex items-center justify-between mb-4 text-sm">
                        <span class="text-slate-600">Price difference:</span>
                        <span x-show="selectedGame.priceDiff > 0" class="text-red-600 font-semibold" x-text="'+' + selectedGame.priceDiff + ' MAD extra'"></span>
                        <span x-show="selectedGame.priceDiff <= 0" class="text-emerald-600 font-semibold">No extra charge</span>
                    </div>
                    <div class="flex gap-3">
                        <button @click="modalOpen = false" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-100 font-medium text-sm">Cancel</button>
                        <button @click="switchGame()" :disabled="switching" class="flex-1 px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 font-medium text-sm disabled:opacity-50" x-text="switching ? 'Switching...' : 'Confirm Switch'"></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse($tables as $table)
                @php
                    $table->load('reservations.game', 'reservations.sessions', 'reservations.user');
                    $todayRes = $table->todayReservations;
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
                    {{ $status === 'ready' ? 'ring-2 ring-amber-500 border-amber-500' : '' }}"
                    data-table-id="{{ $table->id }}">

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
                                        // Use Morocco timezone
                                        $now = \Carbon\Carbon::now('Africa/Casablanca');
                                        $startedAt = \Carbon\Carbon::parse($currentSession->started_at, 'Africa/Casablanca');
                                        $endTime = $startedAt->clone()->addMinutes($currentSession->duration);
                                        $remainingSeconds = max(0, (int) $now->diffInSeconds($endTime, false));
                                    @endphp
                                    <div class="space-y-4">
                                        <!-- Countdown Timer -->
                                        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg p-4 text-center">
                                            <div id="countdown-{{ $table->id }}"
                                                class="text-4xl font-bold text-white"
                                                data-end-time="{{ $endTime->timestamp }}"
                                                data-started-at="{{ $startedAt }}"
                                                data-remaining="{{ $remainingSeconds }}">
                                                {{ floor($remainingSeconds / 60) }}:{{ str_pad($remainingSeconds % 60, 2, '0', STR_PAD_LEFT) }}
                                            </div>
                                            <p class="text-xs text-white/80 mt-1">time remaining</p>
                                        </div>

                                        <!-- Current Game with Switch -->
                                        @php
                                            $currentGame = $currentSession->currentGame ?? $currentReservation->game;
                                            $currentPrice = $currentGame?->price ?? 0;
                                            $switchGames = \App\Models\Game::whereIn('status', ['available', 'busy'])
                                                ->where('id', '!=', $currentSession->current_game_id ?? 0)
                                                ->get()
                                                ->map(function($g) use ($currentPrice, $currentReservation, $currentSession) {
                                                    $startedAt = \Carbon\Carbon::parse($currentSession->started_at);
                                                    $sessionEnd = $startedAt->copy()->addMinutes($currentSession->duration);
                                                    $conflict = \App\Models\ReservationSession::where('id', '!=', $currentSession->id)
                                                        ->where('current_game_id', $g->id)
                                                        ->whereNotIn('status', ['ended', 'cancelled'])
                                                        ->whereHas('reservation', function($q) use ($currentReservation, $sessionEnd, $startedAt) {
                                                            $q->where('date', $currentReservation->date)
                                                              ->where('start_time', '<', $sessionEnd->format('H:i:s'))
                                                              ->where('end_time', '>', $startedAt->format('H:i:s'));
                                                        })->exists();
                                                    return ['id' => $g->id, 'name' => $g->name, 'price' => $g->price, 'available' => !$conflict, 'priceDiff' => $g->price - $currentPrice];
                                                });
                                        @endphp
                                        <div class="py-2">
                                            <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">Playing</span>
                                            <div class="flex items-center justify-between mt-1">
                                                <div class="flex items-center gap-2">
                                                    <span>🎲</span>
                                                    <span class="font-bold text-slate-900">{{ $currentGame?->name ?? 'No game' }}</span>
                                                </div>
                                                <button type="button" 
                                                    @click="openModal({{ $currentReservation->id }}, '{{ $currentGame?->name ?? 'None' }}', {{ $currentPrice }}, {{ json_encode($switchGames) }})"
                                                    class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-medium hover:bg-amber-200 flex items-center gap-1">
                                                    <span>⚡</span> Switch
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Hidden form for switch (used by modal) -->
                                        <form id="switch-form-{{ $currentReservation->id }}" action="{{ route('sessions.updateGame', $currentReservation) }}" method="POST" class="hidden">
                                            @csrf
                                            <input type="hidden" name="game_id" :value="selectedGame?.id">
                                        </form>
                                        @if(false)
                                        <form action="{{ route('sessions.updateGame', $currentReservation) }}" method="POST" class="flex-1">
                                            @csrf
                                            <select name="game_id" onchange="this.form.submit()" class="text-xs border border-slate-300 rounded px-1 py-0.5 bg-white">
                                                <option value="">⚡ Switch</option>
                                                @foreach($switchGames as $game)
                                                    <option value="{{ $game->id }}">{{ $game->name }} ({{ $game->price }} MAD)</option>
                                                @endforeach
                                                    </select>
                                                </form>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Player -->
                                        <div class="py-2">
                                            <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">Customer</span>
                                            <p class="font-semibold text-slate-900">{{ $currentReservation->user->name }}</p>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex gap-2 mt-4">
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

<script>
function gameSwitcher() {
    return {
        modalOpen: false,
        reservationId: null,
        currentGameName: '',
        currentPrice: 0,
        games: [],
        selectedGame: null,
        switching: false,
        toast: false,
        toastMessage: '',
        toastError: '',

        openModal(resId, gameName, price, gameList) {
            this.reservationId = resId;
            this.currentGameName = gameName;
            this.currentPrice = price;
            this.games = gameList;
            this.selectedGame = null;
            this.modalOpen = true;
            this.toast = false;
            this.toastError = '';
        },

        selectGame(game) {
            this.selectedGame = game;
        },

        switchGame() {
            if (!this.selectedGame || this.switching) return;
            this.switching = true;
            
            const form = document.getElementById('switch-form-' + this.reservationId);
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                this.modalOpen = false;
                this.showToast(data.message || 'Game switched successfully!');
                setTimeout(() => location.reload(), 1500);
            })
            .catch(err => {
                this.toastError = 'Failed to switch game';
                setTimeout(() => this.toastError = '', 3000);
                this.switching = false;
            });
        },

        showToast(msg) {
            this.toastMessage = msg;
            this.toast = true;
            setTimeout(() => this.toast = false, 4000);
        }
    };
}
</script>

<script type="module">
    console.log('✅ Push scripts executed successfully!');

    // ---------------------------------------------------------
    // No more workarounds needed! Vanilla JS works perfectly
    // with Laravel Vite as long as you use type="module"
    // ---------------------------------------------------------

    // Live countdown - use data-remaining
    setInterval(function() {
        document.querySelectorAll('[id^="countdown-"]').forEach(function(el) {
            var remainingSeconds = parseInt(el.getAttribute('data-remaining'));

            if (isNaN(remainingSeconds) || remainingSeconds < 0) {
                return;
            }

            if (remainingSeconds === 0) {
                var tableId = el.id.replace('countdown-', '');
                var card = el.closest('.card-surface');
                var endForm = card ? card.querySelector('form[action*="sessions.end"]') : null;

                if (endForm) {
                    fetch(endForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: '_token=' + encodeURIComponent(document.querySelector('meta[name="csrf-token"]').getAttribute('content'))
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    location.reload();
                }
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

<script>
function gameSwitcher() {
    return {
        isOpen: false,
        loading: false,
        switching: false,
        reservationId: null,
        tableRef: '',
        currentGameName: '',
        currentPrice: null,
        games: [],
        selectedGame: null,
        toast: false,
        toastMessage: '',

        init() {
            const self = this;
            window.openGameSwitch = function(reservationId, tableRef) {
                self.openModal(reservationId, tableRef);
            };
        },

        openModal(reservationId, tableRef) {
            this.reservationId = reservationId;
            this.tableRef = tableRef;
            this.selectedGame = null;
            this.isOpen = true;
            this.loading = true;
            
            fetch(`/sessions/${reservationId}/available-games`)
                .then(res => res.json())
                .then(data => {
                    this.currentGameName = data.current_game || 'None';
                    this.currentPrice = data.current_price;
                    this.games = data.games;
                })
                .catch(err => console.error(err))
                .finally(() => this.loading = false);
        },
@endsection
