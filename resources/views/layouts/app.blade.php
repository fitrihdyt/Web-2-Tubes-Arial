<!DOCTYPE html>
<html>
<head>
    <title>BookMe</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        #map { z-index: 0; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-5xl mx-auto p-6">
        @yield('content')
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
</body>
</html>
