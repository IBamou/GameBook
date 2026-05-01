<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'AJI L3BOU') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900">
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(56,189,248,0.18),_transparent_30%),radial-gradient(circle_at_bottom_right,_rgba(236,72,153,0.12),_transparent_22%)]"></div>
            <div class="relative">
                <header class="absolute inset-x-0 top-0 z-20">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between py-6">
                        <a href="{{ url('/') }}" class="text-lg font-semibold tracking-tight text-slate-900">{{ config('app.name', 'AJI L3BOU') }}</a>
                        <div class="flex items-center gap-3">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-100 transition">Log in</a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="inline-flex items-center rounded-full bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-500 transition">Register</a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                </header>

                <main class="relative pt-32 pb-20">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="grid gap-14 lg:grid-cols-[1.2fr_0.8fr] items-center">
                            <div class="space-y-8">
                                <div class="inline-flex rounded-full bg-sky-100 px-4 py-2 text-sm uppercase tracking-[0.35em] text-sky-700 ring-1 ring-sky-100">Board Game Reservations</div>
                                <div class="space-y-6">
                                    <h1 class="text-5xl font-semibold tracking-tight text-slate-950 sm:text-6xl">Discover your next game night and reserve the perfect table.</h1>
                                    <p class="max-w-2xl text-lg leading-8 text-slate-600">Search games by category, book a reservation, and keep track of your play sessions with a polished and professional management experience.</p>
                                </div>

                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                    <a href="{{ route('categories.index') }}" class="btn-primary">Browse categories</a>
                                    <a href="{{ route('games.index') }}" class="btn-secondary">Explore games</a>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-3">
                                    <div class="card-surface p-5">
                                        <h2 class="text-2xl font-semibold text-slate-950">100+</h2>
                                        <p class="mt-2 text-sm text-slate-600">Board games available to explore.</p>
                                    </div>
                                    <div class="card-surface p-5">
                                        <h2 class="text-2xl font-semibold text-slate-950">Fast booking</h2>
                                        <p class="mt-2 text-sm text-slate-600">Reserve your table in just a few clicks.</p>
                                    </div>
                                    <div class="card-surface p-5">
                                        <h2 class="text-2xl font-semibold text-slate-950">Player sessions</h2>
                                        <p class="mt-2 text-sm text-slate-600">Track every match and session with ease.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card-surface p-8 shadow-[0_40px_80px_-40px_rgba(15,23,42,0.2)]">
                                <div class="space-y-5">
                                    <div class="rounded-3xl bg-slate-50 p-5 ring-1 ring-slate-200">
                                        <p class="text-sm uppercase tracking-[0.22em] text-sky-600">Featured category</p>
                                        <h2 class="mt-3 text-3xl font-semibold text-slate-950">Strategy & Adventure</h2>
                                        <p class="mt-3 text-sm leading-6 text-slate-600">Choose a game from a rich collection of immersive strategy titles for your next session.</p>
                                    </div>

                                    <div class="grid gap-4">
                                        <div class="flex items-center justify-between rounded-3xl border border-slate-200 bg-white px-5 py-4">
                                            <span class="text-sm text-slate-600">Quick access</span>
                                            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-sky-700">Fast</span>
                                        </div>
                                        <div class="rounded-3xl border border-slate-200 bg-white p-5">
                                            <p class="text-sm text-slate-600">Login or register to start reserving your next game session.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
