@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header">
            <div class="section-title">
                <h1>Waitlist Management</h1>
                <p>Users waiting for available time slots</p>
            </div>
        </div>

        @if($waitlist->isEmpty())
            <div class="empty-state">
                <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="empty-state-text">No one on the waitlist</p>
            </div>
        @else
            <div class="table-wrapper overflow-x-auto">
                <table class="min-w-full">
                    <thead class="table-head">
                        <tr class="table-head-row">
                            <th class="table-head-cell">User</th>
                            <th class="table-head-cell">Table</th>
                            <th class="table-head-cell">Date & Time</th>
                            <th class="table-head-cell">Status</th>
                            <th class="table-head-cell text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @foreach($waitlist as $item)
                            <tr class="table-row">
                                <td class="table-cell">
                                    <div class="font-semibold">{{ $item->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $item->user->email }}</div>
                                </td>
                                <td class="table-cell">Table {{ $item->table->reference }}</td>
                                <td class="table-cell">
                                    <div>{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</div>
                                    <div class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}</div>
                                </td>
                                <td class="table-cell">
                                    <span class="badge 
                                        {{ $item->status === 'waiting' ? 'badge-pending' : '' }}
                                        {{ $item->status === 'notified' ? 'badge-confirmed' : '' }}
                                        {{ $item->status === 'expired' ? 'badge-cancelled' : '' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="table-cell text-right">
                                    <form action="{{ route('waitlist.delete', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection