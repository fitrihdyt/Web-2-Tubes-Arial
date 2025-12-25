@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">Cari Hotel</h1>

<form method="GET" action="{{ route('dashboard') }}"
      class="bg-white p-4 rounded-xl shadow mb-6 space-y-4">

    <input type="text"
           name="search"
           value="{{ request('search') }}"
           placeholder="Cari nama hotel atau kota..."
           class="w-full border rounded-lg p-2">

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="font-medium">Bintang</label>
            <div class="flex gap-2 mt-1">
                @for ($i = 1; $i <= 5; $i++)
                    <label class="flex items-center gap-1">
                        <input type="checkbox"
                               name="star[]"
                               value="{{ $i }}"
                               {{ in_array($i, request('star', [])) ? 'checked' : '' }}>
                        {{ $i }}★
                    </label>
                @endfor
            </div>
        </div>

        <div>
            <label class="font-medium">Harga</label>
            <select name="price" class="w-full border rounded p-2 mt-1">
                <option value="">Semua</option>
                <option value="0-500" {{ request('price')=='0-500'?'selected':'' }}>
                    ≤ Rp 500.000
                </option>
                <option value="500-1000" {{ request('price')=='500-1000'?'selected':'' }}>
                    Rp 500.000 – 1.000.000
                </option>
                <option value="1000+" {{ request('price')=='1000+'?'selected':'' }}>
                    ≥ Rp 1.000.000
                </option>
            </select>
        </div>

        <div>
            <label class="font-medium">Urutkan</label>
            <select name="sort" class="w-full border rounded p-2 mt-1">
                <option value="">Default</option>
                <option value="price_asc" {{ request('sort')=='price_asc'?'selected':'' }}>
                    Harga Termurah
                </option>
                <option value="price_desc" {{ request('sort')=='price_desc'?'selected':'' }}>
                    Harga Termahal
                </option>
                <option value="star_desc" {{ request('sort')=='star_desc'?'selected':'' }}>
                    Rating Tertinggi
                </option>
            </select>
        </div>

        <!-- BUTTON -->
        <div class="flex items-end">
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg w-full">
                Cari
            </button>
        </div>

    </div>

    <!-- MAP HIDDEN INPUT -->
    <input type="hidden" name="lat" id="lat" value="{{ request('lat') }}">
    <input type="hidden" name="lng" id="lng" value="{{ request('lng') }}">
</form>

<div class="bg-white rounded-xl shadow p-4 mb-6">
    <h2 class="font-semibold mb-2">Pilih Lokasi (Klik Map)</h2>
    <div id="map" class="w-full h-72 rounded-lg border"></div>
</div>

@if($hotels->isEmpty())
    <div class="bg-white p-6 rounded shadow text-center text-gray-500">
        Hotel tidak ditemukan
    </div>
@else
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($hotels as $hotel)
    <a href="{{ route('hotels.show', $hotel) }}"
       class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">

        <div class="h-44 bg-gray-100">
            @if($hotel->thumbnail)
                <img src="{{ asset('storage/'.$hotel->thumbnail) }}"
                     class="w-full h-full object-cover">
            @endif
        </div>

        <div class="p-4">
            <h2 class="font-semibold text-lg">{{ $hotel->name }}</h2>

            <div class="text-yellow-500 text-sm">
                {{ str_repeat('★', $hotel->star) }}
            </div>

            <p class="text-gray-500">{{ $hotel->city }}</p>

            <p class="text-sm mt-2 text-blue-600 font-semibold">
                Mulai dari Rp {{ number_format($hotel->rooms_min_price ?? 0) }}
            </p>
        </div>
    </a>
    @endforeach
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('map').setView([-6.2, 106.8], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let marker;

    map.on('click', function (e) {
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }

        document.getElementById('lat').value = e.latlng.lat;
        document.getElementById('lng').value = e.latlng.lng;
    });
});
</script>
@endsection
