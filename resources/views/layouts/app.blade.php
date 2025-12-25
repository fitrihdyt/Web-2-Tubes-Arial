<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookMe</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
</head>

<body class="bg-gray-100 min-h-screen">

<!-- NAVBAR -->
<nav class="bg-white shadow">
    <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
        <span class="font-bold text-lg">BookMe</span>

        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('hotels.index') }}" class="hover:text-blue-600">Hotel</a>
            <a href="{{ route('rooms.index') }}" class="hover:text-blue-600">Rooms</a>
            <a href="{{ route('bookings.index') }}" class="hover:text-blue-600">Booking</a>

            <span class="text-gray-400">|</span>

            <span class="text-gray-600">
                {{ auth()->user()->name }}
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-red-600 hover:underline">Logout</button>
            </form>
        </div>
    </div>
</nav>

<!-- CONTENT -->
<main class="max-w-6xl mx-auto px-6 py-8">
    @yield('content')
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
</body>
</html>
