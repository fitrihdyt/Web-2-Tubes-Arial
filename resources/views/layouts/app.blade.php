<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookMe</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
</head>

<body class="bg-neutral-50 min-h-screen">

<!-- NAVBAR -->
<nav class="bg-white border-b sticky top-0 z-50">
    <div class="w-full px-10 md:px-20 h-16 flex items-center justify-between">

        <!-- LEFT -->
        <div class="flex items-center gap-10">
            <a href="{{ route('dashboard') }}"
               class="text-xl font-semibold text-neutral-900">
                BookMe
            </a>

            <div class="hidden md:flex gap-8 text-sm font-medium text-neutral-600">
                <a href="{{ route('dashboard') }}"
                   class="{{ request()->routeIs('dashboard') ? 'text-neutral-900 font-semibold' : 'hover:text-neutral-900' }}">
                    Home
                </a>

                @auth
                    <a href="{{ route('bookings.index') }}"
                       class="{{ request()->routeIs('bookings.index') ? 'text-neutral-900 font-semibold' : 'hover:text-neutral-900' }}">
                        Bookings
                    </a>

                    <!-- {{-- HISTORI (USER BIASA SAJA) --}}
                    @if(auth()->user()->role !== 'admin')
                        <a href="{{ route('bookings.history') }}"
                           class="{{ request()->routeIs('bookings.history')
                               ? 'text-neutral-900 font-semibold'
                               : 'hover:text-neutral-900' }}">
                            Histori
                        </a>
                    @endif -->

                    {{-- MENU ADMIN --}}
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('hotels.index') }}"
                           class="{{ request()->routeIs('hotels.*') ? 'text-neutral-900 font-semibold' : 'hover:text-neutral-900' }}">
                            Hotels
                        </a>

                        <a href="{{ route('rooms.index') }}"
                           class="{{ request()->routeIs('rooms.*') ? 'text-neutral-900 font-semibold' : 'hover:text-neutral-900' }}">
                            Rooms
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        <!-- RIGHT -->
        <div class="flex items-center gap-6 text-sm">

            @guest
                <a href="{{ route('login') }}" class="text-neutral-600 hover:text-neutral-900">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="bg-neutral-900 text-white px-4 py-2 rounded-lg hover:bg-neutral-800 transition">
                    Register
                </a>
            @endguest

            @auth
                <!-- PROFILE BUTTON -->
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 px-3 py-1.5 rounded-lg hover:bg-neutral-100 transition">

                    <!-- AVATAR -->
                    <img
                        src="{{ auth()->user()->avatar
                            ? asset('storage/' . auth()->user()->avatar)
                            : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name)
                        }}"
                        alt="Avatar"
                        class="w-9 h-9 rounded-full object-cover border"
                    >

                    <!-- USERNAME -->
                    <span class="text-neutral-700 font-medium">
                        {{ auth()->user()->name }}
                    </span>
                </a>

                <!-- LOGOUT -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-500 hover:underline">
                        Logout
                    </button>
                </form>
            @endauth

        </div>
    </div>
</nav>

<!-- CONTENT -->
<main class="w-full">
    @yield('content')
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
</body>
</html>
