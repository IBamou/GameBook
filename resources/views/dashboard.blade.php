@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header mb-12">
            <div class="section-title">
                <h1>Welcome back, {{ explode(' ', auth()->user()->name)[0] }}!</h1>
                <p>Manage your games, tables, and reservations</p>
            </div>
            <a href="{{ route('games.index') }}" class="btn-primary">Browse Games</a>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
            <!-- Quick Stats -->
            <div class="card-surface p-6">
                <p class="stat-label text-sky-600">Total Reservations</p>
                <h3 class="stat-value mt-2">{{ auth()->user()->reservations->count() }}</h3>
                <p class="mt-4 text-sm text-slate-600">Your booking history</p>
                <a href="{{ route('reservations.my') }}" class="inline-block mt-4 text-sky-600 font-medium hover:text-sky-700">View reservations →</a>
            </div>

            <!-- Categories Access -->
            <div class="card-surface p-6">
                <p class="stat-label">Browse by Category</p>
                <h3 class="mt-4 text-xl font-semibold text-slate-950">Explore games</h3>
                <p class="mt-3 text-sm text-slate-600">Discover new games organized by category</p>
                <div class="mt-6 flex gap-3">
                    <a href="{{ route('categories.index') }}" class="btn-secondary text-sm flex-1">Categories</a>
                    <a href="{{ route('games.index') }}" class="btn-secondary text-sm flex-1">All Games</a>
                </div>
            </div>

            <!-- Reservations Quick Access -->
            <div class="card-surface p-6">
                <p class="stat-label text-emerald-600">Make a Reservation</p>
                <h3 class="mt-4 text-xl font-semibold text-slate-950">Book a table</h3>
                <p class="mt-3 text-sm text-slate-600">Reserve a gaming table for your next session</p>
                <div class="mt-6">
                    <a href="{{ route('reservations.create') }}" class="btn-primary w-full">New Reservation</a>
                </div>
            </div>

            @if(auth()->user()->role === 'admin')
            <!-- Admin: Tables -->
            <div class="card-surface p-6 lg:col-span-1">
                <p class="stat-label text-amber-600">Admin Panel</p>
                <h3 class="mt-4 text-xl font-semibold text-slate-950">Manage Tables</h3>
                <p class="mt-3 text-sm text-slate-600">Configure tables and capacity</p>
                <div class="mt-6">
                    <a href="{{ route('tables.index') }}" class="btn-secondary w-full text-sm">View Tables</a>
                </div>
            </div>

            <!-- Admin: All Reservations -->
            <div class="card-surface p-6 lg:col-span-1">
                <p class="stat-label text-blue-600">Admin Panel</p>
                <h3 class="mt-4 text-xl font-semibold text-slate-950">Reservations</h3>
                <p class="mt-3 text-sm text-slate-600">Review and manage all bookings</p>
                <div class="mt-6">
                    <a href="{{ route('reservations.index') }}" class="btn-secondary w-full text-sm">All Reservations</a>
                </div>
            </div>

            <!-- Admin: Sessions -->
            <div class="card-surface p-6 lg:col-span-1">
                <p class="stat-label text-emerald-600">Admin Panel</p>
                <h3 class="mt-4 text-xl font-semibold text-slate-950">Sessions</h3>
                <p class="mt-3 text-sm text-slate-600">Track and manage game sessions</p>
                <div class="mt-6">
                    <a href="{{ route('sessions.index') }}" class="btn-secondary w-full text-sm">Session Dashboard</a>
                </div>
            </div>
            @endif
        </div>

        <!-- Recent Activity -->
        @if(auth()->user()->reservations->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-slate-950 mb-6">Recent Reservations</h2>
            <div class="space-y-3">
                @foreach(auth()->user()->reservations->take(3) as $reservation)
                    <a href="{{ route('reservations.show', $reservation) }}" class="group card-surface p-4 flex items-center justify-between hover:border-sky-300">
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-slate-950 group-hover:text-sky-600 transition-colors">Table {{ $reservation->table->reference }}</p>
                            <p class="text-sm text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($reservation->date)->format('d M Y') }} at {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}</p>
                        </div>
                        <span class="
                            {{ $reservation->status === 'confirmed' ? 'badge-confirmed' : '' }}
                            {{ $reservation->status === 'pending' ? 'badge-pending' : '' }}
                            {{ $reservation->status === 'cancelled' ? 'badge-cancelled' : '' }}
                            {{ $reservation->status === 'completed' ? 'badge-completed' : '' }}
                        ">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
