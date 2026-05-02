@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header">
            <div class="section-title">
                <h1>My Waitlist</h1>
                <p>Time slots you're waiting for</p>
            </div>
        </div>

        @if($waitlist->isEmpty())
            <div class="empty-state">
                <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="empty-state-text">You're not on any waitlist</p>
                <a href="{{ route('tables.index') }}" class="btn-primary mt-4">Browse Tables</a>
            </div>
        @else
            <div class="grid gap-4">
                @foreach($waitlist as $item)
                    <div class="card-surface p-4 flex items-center justify-between">
                        <div>
                            <div class="font-semibold">Table {{ $item->table->reference }}</div>
                            <div class="text-sm text-slate-600">{{ \Carbon\Carbon::parse($item->date)->format('l, F j, Y') }}</div>
                            <div class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="badge 
                                {{ $item->status === 'waiting' ? 'badge-pending' : '' }}
                                {{ $item->status === 'notified' ? 'badge-confirmed' : '' }}
                                {{ $item->status === 'expired' ? 'badge-cancelled' : '' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                            <form action="{{ route('waitlist.delete', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Leave</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection