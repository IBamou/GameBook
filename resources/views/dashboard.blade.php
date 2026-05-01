<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-slate-900 dark:text-white leading-tight">{{ __('Dashboard') }}</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">A polished control center for your games, reservations, and sessions.</p>
            </div>
            <a href="{{ route('games.index') }}" class="inline-flex items-center justify-center rounded-full bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-500 transition">Browse games</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="grid gap-6 xl:grid-cols-3">
            <div class="card-surface p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-sky-500">Welcome back</p>
                <h3 class="mt-4 text-2xl font-semibold text-slate-900 dark:text-white">Ready to play?</h3>
                <p class="mt-3 text-slate-600 dark:text-slate-300">Manage your reservations, discover new games, and keep every session on track from one beautiful dashboard.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('categories.index') }}" class="btn-primary">Categories</a>
                    <a href="{{ route('reservations.my') }}" class="btn-secondary">My Reservations</a>
                </div>
            </div>

            <div class="card-surface p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-slate-500">Reservations</p>
                <h3 class="mt-4 text-2xl font-semibold text-slate-900 dark:text-white">Quick access</h3>
                <p class="mt-3 text-slate-600 dark:text-slate-300">View and update your upcoming reservations or create a new reservation in seconds.</p>
                <div class="mt-6">
                    <a href="{{ route('reservations.my') }}" class="inline-flex items-center justify-center rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 shadow-sm shadow-slate-900/5 hover:bg-slate-50 transition">Go to reservations</a>
                </div>
            </div>

            <div class="card-surface p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-slate-500">Sessions</p>
                <h3 class="mt-4 text-2xl font-semibold text-slate-900 dark:text-white">Track your progress</h3>
                <p class="mt-3 text-slate-600 dark:text-slate-300">Keep tabs on your active sessions and update game progress while you play.</p>
                <div class="mt-6">
                    <a href="{{ route('sessions.my') }}" class="inline-flex items-center justify-center rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 shadow-sm shadow-slate-900/5 hover:bg-slate-50 transition">View sessions</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
