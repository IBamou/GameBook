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
                        <div class="card-surface p-5 flex items-center justify-between">
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
@endsection