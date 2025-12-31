@extends('layouts.app')

@section('content')
<div class="w-full bg-[#f5f7fa] min-h-screen">

    <!-- HERO -->
    <section class="relative w-full h-[520px]">
        <img
            src="https://i.pinimg.com/1200x/02/5b/3f/025b3fb3bd9ad83b8c0d8a89b1d67794.jpg"
            class="absolute inset-0 w-full h-full object-cover"
        >
        <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-black/20"></div>

        <div class="relative h-full flex flex-col justify-center px-6 md:px-20">
            <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight">
                Booking Hotel & Penginapan Murah
            </h1>
            <p class="mt-4 text-white/90 text-lg max-w-2xl">
                Temukan hotel terbaik di seluruh Indonesia dengan harga promo dan proses cepat.
            </p>

            <!-- SEARCH CARD -->
            <form method="GET" action="{{ route('dashboard') }}"
                class="mt-10 bg-white rounded-2xl shadow-2xl p-6 grid grid-cols-1 md:grid-cols-5 gap-4 max-w-5xl">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Kota, tujuan, atau nama hotel"
                    class="md:col-span-2 px-5 py-4 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-600 focus:outline-none"
                >

                <select
                    name="price"
                    class="px-5 py-4 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <option value="">Semua Harga</option>
                    <option value="0-500" {{ request('price')=='0-500'?'selected':'' }}>Under 500k</option>
                    <option value="500-1000" {{ request('price')=='500-1000'?'selected':'' }}>500k – 1jt</option>
                    <option value="1000+" {{ request('price')=='1000+'?'selected':'' }}>Di atas 1jt</option>
                </select>

                <button
                    class="md:col-span-2 bg-blue-600 hover:bg-blue-700 transition text-white font-semibold rounded-xl py-4">
                    Cari Hotel
                </button>
            </form>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="w-full px-6 md:px-20 py-16">
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-10">

            <!-- HOTEL LIST -->
            <div class="xl:col-span-3">
                @if($hotels->isEmpty())
                    <div class="bg-white rounded-2xl p-20 text-center shadow">
                        <p class="text-gray-400 text-lg">Hotel tidak ditemukan.</p>
                    </div>
                @else
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($hotels as $hotel)
                        <a href="{{ route('hotels.show', $hotel) }}"
                            class="group bg-white rounded-2xl overflow-hidden shadow hover:shadow-xl transition">

                            <div class="relative h-56">
                                <img
                                    src="{{ $hotel->thumbnail ? asset('storage/'.$hotel->thumbnail) : 'https://via.placeholder.com/400x300' }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                >

                                <span
                                    class="absolute top-4 left-4 bg-white px-3 py-1 rounded-full text-sm font-bold text-blue-600">
                                    ★ {{ $hotel->star }}
                                </span>
                            </div>

                            <div class="p-5">
                                <p class="text-xs uppercase tracking-widest text-gray-400">
                                    {{ $hotel->city }}
                                </p>

                                <h3 class="text-lg font-semibold text-gray-900 mt-1">
                                    {{ $hotel->name }}
                                </h3>

                                <div class="mt-4 flex justify-between items-end">
                                    <div>
                                        <p class="text-xs text-gray-400">Mulai dari</p>
                                        <p class="text-lg font-bold text-blue-600">
                                            Rp {{ number_format($hotel->rooms_min_price ?? 0, 0, ',', '.') }}
                                            <span class="text-sm font-normal text-gray-500">/malam</span>
                                        </p>
                                    </div>

                                    <div
                                        class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-50 group-hover:bg-blue-600 group-hover:text-white transition">
                                        →
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- MAP -->
            <div class="xl:col-span-1">
                <div class="sticky top-24 bg-white rounded-2xl shadow overflow-hidden">
                    <div class="px-5 py-4 border-b">
                        <h3 class="font-semibold text-gray-900">Cari Berdasarkan Area</h3>
                        <p class="text-xs text-gray-400 mt-1">Klik peta untuk memilih lokasi</p>
                    </div>
                    <div id="map" class="h-[520px]"></div>
                </div>
            </div>

        </div>
    </section>
</div>

<style>
    #map { cursor: crosshair }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('map', { scrollWheelZoom: false })
        .setView([-6.2, 106.8], 11)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map)

    let marker
    map.on('click', e => {
        if (marker) marker.setLatLng(e.latlng)
        else marker = L.marker(e.latlng).addTo(map)
    })
})
</script>
@endsection
