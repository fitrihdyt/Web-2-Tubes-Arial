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
                <a href="{{ route('dashboard') }}" class="hover:text-neutral-900">
                    Home
                </a>

                @auth
                    <a href="{{ route('bookings.index') }}" class="hover:text-neutral-900">
                        Bookings
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('hotels.index') }}" class="hover:text-neutral-900">
                            Hotels
                        </a>
                        <a href="{{ route('rooms.index') }}" class="hover:text-neutral-900">
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
                   class="bg-neutral-900 text-white px-4 py-2 rounded-lg hover:bg-neutral-800">
                    Register
                </a>
            @endguest

            @auth
                <span class="text-neutral-600">{{ auth()->user()->name }}</span>

                <a href="{{ route('profile.edit') }}" class="hover:text-neutral-900">
                    Profile
                </a>

                <a href="{{ route('settings.index') }}" class="text-gray-600 hover:text-blue-600"> 
                    Settings </a>
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
