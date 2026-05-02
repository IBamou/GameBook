<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="text-lg font-semibold tracking-tight text-slate-900">AJI L3bou</a>
            </div>

            <div class="hidden sm:flex sm:items-center sm:space-x-2">
                <x-nav-link :href="url('/')" :active="request()->is('/')">
                    {{ __('Home') }}
                </x-nav-link>
                <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                    {{ __('Categories') }}
                </x-nav-link>
                <x-nav-link :href="route('games.index')" :active="request()->routeIs('games.*')">
                    {{ __('Games') }}
                </x-nav-link>
                @auth
                    @can('admin')
                        <x-dropdown align="left" width="40">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-700 hover:text-slate-900 focus:outline-none transition ease-in-out duration-150">
                                    {{ __('Reservations') }}
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('reservations.my')">
                                    {{ __('Mine') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('reservations.index')">
                                    {{ __('All') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                        <x-dropdown align="left" width="40">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-700 hover:text-slate-900 focus:outline-none transition ease-in-out duration-150">
                                    {{ __('Sessions') }}
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('sessions.my')">
                                    {{ __('Mine') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('sessions.index')">
                                    {{ __('All') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <x-nav-link :href="route('reservations.my')" :active="request()->routeIs('reservations.index')">
                            {{ __('My Reservations') }}
                        </x-nav-link>
                        <x-nav-link :href="route('sessions.my')" :active="request()->routeIs('sessions.my')">
                            {{ __('My Sessions') }}
                        </x-nav-link>
                    @endcan
                @endauth
            </div>

            <div class="hidden sm:flex sm:items-center sm:space-x-3">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-slate-700 bg-slate-100 hover:bg-slate-200 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">
                            {{ __('Log in') }}
                        </a>
                    @endif

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center rounded-full bg-sky-600 px-4 py-2 text-sm font-medium text-white hover:bg-sky-500 transition">
                            {{ __('Register') }}
                        </a>
                    @endif
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-full text-slate-600 hover:bg-slate-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="url('/')" :active="request()->is('/')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                {{ __('Categories') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('games.index')" :active="request()->routeIs('games.*')">
                {{ __('Games') }}
            </x-responsive-nav-link>
            @auth
                @can('admin')
                    <div class="pl-4 py-2">
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Reservations</div>
                    </div>
                    <x-responsive-nav-link :href="route('reservations.my')" :active="request()->routeIs('reservations.my')">
                        {{ __('Mine') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('reservations.index')" :active="request()->routeIs('reservations.index')">
                        {{ __('All') }}
                    </x-responsive-nav-link>
                    <div class="pl-4 py-2">
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sessions</div>
                    </div>
                    <x-responsive-nav-link :href="route('sessions.my')" :active="request()->routeIs('sessions.my')">
                        {{ __('Mine') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('sessions.index')" :active="request()->routeIs('sessions.index')">
                        {{ __('All') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('reservations.my')" :active="request()->routeIs('reservations.my')">
                        {{ __('My Reservations') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('sessions.my')" :active="request()->routeIs('sessions.my')">
                        {{ __('My Sessions') }}
                    </x-responsive-nav-link>
                @endcan
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-slate-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-slate-900">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1 px-4">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4 space-y-1">
                    @if (Route::has('login'))
                        <x-responsive-nav-link :href="route('login')">
                            {{ __('Log in') }}
                        </x-responsive-nav-link>
                    @endif

                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>
