@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header">
            <div class="section-title">
                <h1>Reservations</h1>
                <p>Manage all game table reservations</p>
            </div>
        </div>

        <form method="GET" class="mb-6 flex flex-wrap gap-3 items-center">
            <div class="min-w-[180px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user name..." 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500">
            </div>
            <div class="min-w-[140px]">
                <input type="date" name="date" value="{{ request('date') }}" 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500">
            </div>
            <select name="status" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            <select name="table" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                <option value="">All Tables</option>
                @foreach(\App\Models\Table::all() as $table)
                    <option value="{{ $table->id }}" {{ request('table') == $table->id ? 'selected' : '' }}>Table {{ $table->reference }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
            @if(request()->anyFilled(['search', 'status', 'date', 'table']))
                <a href="{{ route('reservations.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Clear</a>
            @endif
        </form>

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
                    @forelse($reservations as $reservation)
                        <tr class="table-row">
                            <td class="table-cell">
                                <div class="font-semibold">{{ $reservation->user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $reservation->user->email }}</div>
                            </td>
                            <td class="table-cell">{{ $reservation->table->reference }}</td>
                            <td class="table-cell">
                                <div>{{ \Carbon\Carbon::parse($reservation->date)->format('d M Y') }}</div>
                                <div class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}</div>
                            </td>
                            <td class="table-cell">
                                <span class="
                                    {{ $reservation->status === 'confirmed' ? 'badge-confirmed' : '' }}
                                    {{ $reservation->status === 'pending' ? 'badge-pending' : '' }}
                                    {{ $reservation->status === 'cancelled' ? 'badge-cancelled' : '' }}
                                    {{ $reservation->status === 'completed' ? 'badge-completed' : '' }}
                                ">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                            <td class="table-cell text-right">
                                <a href="{{ route('reservations.show', $reservation) }}" class="text-sky-600 hover:text-sky-700 font-medium">View</a>
                                @if($reservation->status === 'pending')
                                    <form action="{{ route('reservations.status', $reservation) }}" method="POST" class="inline ml-4">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-700 font-medium">Confirm</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8">
                                <div class="empty-state">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="empty-state-text">No reservations found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
