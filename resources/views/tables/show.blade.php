@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-4xl font-bold text-slate-950">Table {{ $table->reference }}</h1>
                <p class="mt-2 text-slate-600">{{ $table->capacity }} seats</p>
            </div>
            @auth
                @if(auth()->user()->role === 'admin')
                <div class="flex gap-2">
                    <a href="{{ route('tables.edit', $table) }}" class="btn-secondary">Edit</a>
                    <form action="{{ route('tables.delete', $table) }}" method="POST" onsubmit="return confirm('Delete this table?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-secondary text-red-600 hover:bg-red-50">Delete</button>
                    </form>
                </div>
                @endif
            @endauth
        </div>

        <div class="card-surface p-6 mb-8 max-w-md">
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Table Reference</p>
                    <p class="mt-2 text-2xl font-bold text-slate-950">{{ $table->reference }}</p>
                </div>
                <div class="border-t border-slate-200 pt-4">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Maximum Capacity</p>
                    <p class="mt-2 text-2xl font-bold text-slate-950">{{ $table->capacity }} people</p>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-2xl font-bold text-slate-950 mb-4">Today's Reservations</h2>
            <div class="space-y-3">
                @forelse($table->todayReservations->where('status', 'confirmed') as $res)
                    <a href="{{ route('reservations.show', $res) }}" class="group card-surface p-4 flex items-center justify-between hover:border-sky-300">
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-950 group-hover:text-sky-600 transition-colors">{{ $res->user->name }}</p>
                            <p class="text-sm text-slate-500 mt-1">
                                {{ \Carbon\Carbon::parse($res->start_time)->format('H:i') }} – 
                                {{ \Carbon\Carbon::parse($res->end_time)->format('H:i') }}
                            </p>
                        </div>
                        <span class="
                            {{ $res->status === 'confirmed' ? 'badge-confirmed' : '' }}
                            {{ $res->status === 'pending' ? 'badge-pending' : '' }}
                            {{ $res->status === 'cancelled' ? 'badge-cancelled' : '' }}
                        ">
                            {{ ucfirst($res->status) }}
                        </span>
                    </a>
                @empty
                    <div class="empty-state py-12">
                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="empty-state-text">No reservations for today</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
