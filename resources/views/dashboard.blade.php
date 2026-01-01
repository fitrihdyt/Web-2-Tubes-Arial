@extends('layouts.app')

@section('content')
<div class="bg-[#eef6f8] min-h-screen pt-23">

    {{-- HERO --}}
    <section class="max-w-7xl mx-auto px-6">
        <div class="relative h-[420px] rounded-[2.5rem] overflow-hidden shadow-2xl">

            <img
                src="https://i.pinimg.com/1200x/02/5b/3f/025b3fb3bd9ad83b8c0d8a89b1d67794.jpg"
                class="absolute inset-0 w-full h-full object-cover scale-105"
            >

            <div class="absolute inset-0 bg-gradient-to-r from-[#0f3a4e]/80 to-[#0f3a4e]/30"></div>

            <div class="relative h-full flex flex-col justify-center px-10 md:px-16 text-white">
                <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                    Temukan Hotel Terbaik
                </h1>
                <p class="mt-4 text-white/90 max-w-xl text-lg">
                    Pengalaman booking hotel yang nyaman, cepat, dan terpercaya.
                </p>

                {{-- SEARCH --}}
                <form method="GET"
                      action="{{ route('dashboard') }}"
                      class="mt-10 bg-white/95 backdrop-blur rounded-2xl shadow-xl p-6
                             grid grid-cols-1 md:grid-cols-6 gap-4 max-w-6xl">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Kota atau nama hotel"
                        class="md:col-span-3 px-5 py-4 rounded-xl border border-gray-200
                                text-[#134662] placeholder:text-gray-500
                               focus:ring-2 focus:ring-[#134662]"
                    >

                    <select
                        name="price"
                        class="md:col-span-2 px-5 py-4 rounded-xl border border-gray-200
                                 text-[#134662] 
                               focus:ring-2 focus:ring-[#134662]">
                        <option value="">Semua Harga</option>

                        <option value="0-500"
                            {{ request('price') == '0-500' ? 'selected' : '' }}>
                            < Rp 500.000
                        </option>

                        <option value="500-1000"
                            {{ request('price') == '500-1000' ? 'selected' : '' }}>
                            Rp 500.000 - Rp 1.000.000
                        </option>

                        <option value="1000+"
                            {{ request('price') == '1000+' ? 'selected' : '' }}>
                            > Rp 1.000.000
                        </option>
                    </select>

                    {{-- BUTTON SEARCH ICON --}}
                    <button
                        type="submit"
                        class="flex items-center justify-center bg-[#134662]
                               hover:bg-[#0f3a4e] transition
                               text-white rounded-xl">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-6 h-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M21 21l-4.35-4.35m1.85-5.65a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                </form>
            </div>
        </div>
    </section>

    {{-- HOTEL LIST --}}
    <section class="max-w-7xl mx-auto px-6 py-20">

        <h2 class="text-2xl font-bold text-[#134662] mb-6">
            Rekomendasi Hotel
        </h2>

        @if($hotels->isEmpty())
            <div class="bg-white rounded-3xl p-20 text-center shadow">
                <p class="text-gray-400 text-lg">Hotel tidak ditemukan.</p>
            </div>
        @else
            <div class="relative -mx-6">

                {{-- SCROLL AREA --}}
                <div
                    class="flex gap-8 overflow-x-auto px-6 pb-10
                        scroll-smooth snap-x snap-mandatory
                        [&::-webkit-scrollbar]:hidden">

                    {{-- HOTEL CARD --}}
                    @foreach($hotels as $hotel)
                        <a href="{{ route('hotels.show', $hotel) }}"
                        class="snap-start min-w-[280px] bg-white rounded-3xl
                                overflow-hidden shadow
                                hover:shadow-xl transition group">

                            {{-- IMAGE --}}
                            <div class="relative h-48 bg-gray-100">
                                <img
                                    src="{{ $hotel->thumbnail
                                        ? asset('storage/'.$hotel->thumbnail)
                                        : 'https://via.placeholder.com/400x300' }}"
                                    class="w-full h-full object-cover
                                        group-hover:scale-105 transition duration-500">

                                {{-- STAR --}}
                                <div class="absolute top-4 left-4 flex items-center gap-1
                                            bg-white/90 backdrop-blur px-3 py-1 rounded-full">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $hotel->star ? 'text-yellow-400' : 'text-gray-300' }}"
                                            viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921
                                                    1.902 0l1.286 3.967a1
                                                    1 0 00.95.69h4.178c.969
                                                    0 1.371 1.24.588
                                                    1.81l-3.385
                                                    2.46a1 1 0 00-.364
                                                    1.118l1.287
                                                    3.966c.3.922-.755
                                                    1.688-1.54
                                                    1.118l-3.386-2.46a1
                                                    1 0 00-1.175
                                                    0l-3.386
                                                    2.46c-.784.57-1.838-.196-1.539-1.118l1.286-3.966a1
                                                    1 0 00-.364-1.118L2.047
                                                    8.394c-.783-.57-.38-1.81.588-1.81h4.178a1
                                                    1 0 00.95-.69l1.286-3.967z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>

                            {{-- CONTENT --}}
                            <div class="p-6">
                                <p class="text-xs uppercase tracking-widest text-gray-400">
                                    {{ $hotel->city }}
                                </p>

                                <h3 class="text-lg font-semibold text-gray-900 mt-1">
                                    {{ $hotel->name }}
                                </h3>

                                <div class="mt-5 flex justify-between items-end">
                                    <div>
                                        <p class="text-xs text-gray-400">Mulai dari</p>
                                        <p class="text-lg font-bold text-[#134662]">
                                            Rp {{ number_format($hotel->rooms_min_price ?? 0, 0, ',', '.') }}
                                            <span class="text-sm font-normal text-gray-500">/ malam</span>
                                        </p>
                                    </div>

                                    {{-- ARROW --}}
                                    <div class="w-10 h-10 flex items-center justify-center
                                                rounded-full bg-[#eef6f8]
                                                group-hover:bg-[#134662]
                                                group-hover:text-white transition">
                                        <svg class="w-5 h-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach

                    {{-- MORE CARD --}}
                    <a href="{{ route('hotels.index') }}"
                    class="snap-start min-w-[280px] flex flex-col
                            items-center justify-center
                            border-2 border-dashed border-[#134662]/30
                            rounded-3xl text-center
                            hover:bg-[#134662]/5 transition">

                        <div class="w-14 h-14 flex items-center justify-center
                                    rounded-full bg-[#eef6f8] mb-4">
                            <svg class="w-6 h-6 text-[#134662]"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>

                        <p class="text-lg font-semibold text-[#134662]">
                            Lihat Semua Hotel
                        </p>

                        <p class="text-sm text-gray-400 mt-1">
                            Jelajahi lebih banyak pilihan
                        </p>
                    </a>

                </div>

                {{-- SCROLL INDICATOR --}}
                <div class="flex justify-center mt-4">
                    <div class="w-24 h-1 rounded-full bg-gray-200 overflow-hidden">
                        <div class="w-1/3 h-full bg-[#134662]"></div>
                    </div>
                </div>

            </div>
        @endif
    </section>      


    <!-- MAP SECTION (PINDAH KE BAWAH) -->
    {{-- MAP + LIST --}}
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden relative">

            <div class="px-8 py-6 border-b">
                <h3 class="text-lg font-semibold text-[#134662]">
                    Cari Berdasarkan Lokasi
                </h3>
                <p class="text-sm text-gray-400">
                    Klik peta atau gunakan lokasi kamu
                </p>
            </div>

            {{-- BUTTON LOKASI --}}
            <button id="myLocation"
                class="absolute top-24 right-6 z-10 bg-white px-4 py-2 rounded-xl shadow
                       flex items-center gap-2 text-sm font-semibold text-slate-700
                       hover:bg-gray-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 11.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19.5 11.5c0 7-7.5 10.5-7.5 10.5S4.5 18.5 4.5 11.5
                             a7.5 7.5 0 1115 0z"/>
                </svg>
                Lokasi Saya
            </button>

            <div id="map" class="h-[420px]"></div>

            {{-- LIST HOTEL SEKitar --}}
            <div class="p-8 border-t">
                <h4 class="font-semibold text-[#134662] mb-6">
                    Hotel di Sekitar Lokasi
                </h4>

                <div id="nearbyHotels"
                     class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <p class="text-gray-400 col-span-full">
                        Pilih lokasi untuk melihat hotel terdekat.
                    </p>
                </div>
            </div>

        </div>
    </section>
    <footer class="bg-[#0f3a4e] text-white mt-32">
        <div class="max-w-7xl mx-auto px-6 py-12">

            <!-- TOP -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-start">

                <!-- LEFT -->
                <div>
                    <h3 class="text-3xl font-semibold mb-5">BookMe.</h3>

                    <p class="text-sm text-white/75 leading-relaxed max-w-md">
                        BookMe adalah platform booking hotel yang membantu kamu
                        menemukan penginapan terbaik dengan proses cepat, aman,
                        dan nyaman di seluruh Indonesia.
                    </p>

                    <p class="mt-5 text-sm italic text-white/50">
                        Making your stay easier, one booking at a time.
                    </p>

                    <a href="#"
                    class="inline-flex items-center gap-2 mt-8 px-6 py-3 rounded-full
                            border border-white/25 text-sm font-medium
                            hover:bg-white hover:text-[#0f3a4e] transition">
                        Tentang Tim Kami
                    </a>
                </div>

                <!-- RIGHT -->
                <div class="md:justify-self-end">
                    <h4 class="text-xs font-semibold tracking-widest text-white/70 mb-6">
                        HELP CENTER
                    </h4>

                    <ul class="space-y-5 text-sm text-white/75">

                        <li class="flex items-center gap-4">
                            <span class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            support@bookme.id
                        </li>

                        <li class="flex items-center gap-4">
                            <span class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.128a11.042 11.042 0 005.516 5.516l1.128-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.163 21 3 14.837 3 7V5z"/>                                </svg>
                            </span>
                            +62 812-3456-7890
                        </li>

                        <li class="flex items-center gap-4">
                            <span class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 11c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11c0 5-7 10-7 10S5 16 5 11a7 7 0 1114 0z"/>
                                </svg>
                            </span>
                            Jakarta, Indonesia
                        </li>

                    </ul>
                </div>

            </div>

            <!-- DIVIDER -->
            <div class="mt-8 border-t border-white/10"></div>

            <!-- BOTTOM -->
            <div class="mt-8 flex flex-col md:flex-row justify-between items-center
                        text-sm text-white/50 gap-4">

                <span>
                    © {{ date('Y') }} BookMe. All rights reserved.
                </span>

                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                </div>
            </div>

        </div>
    </footer>



</div>

<style>
    #map { cursor: crosshair }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const map = L.map('map', { scrollWheelZoom: false })
        .setView([-6.2, 106.8], 11)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map)

    let userMarker
    let hotelMarkers = L.layerGroup().addTo(map)

    // KLIK MAP
    map.on('click', e => {
        const { lat, lng } = e.latlng

        if (userMarker) userMarker.setLatLng(e.latlng)
        else userMarker = L.marker(e.latlng).addTo(map)

        loadNearbyHotels(lat, lng)
    })

    // BUTTON LOKASI SAYA
    document.getElementById('myLocation').onclick = () => {
        navigator.geolocation.getCurrentPosition(pos => {
            const lat = pos.coords.latitude
            const lng = pos.coords.longitude

            map.setView([lat, lng], 13)

            if (userMarker) userMarker.setLatLng([lat, lng])
            else userMarker = L.marker([lat, lng]).addTo(map)

            loadNearbyHotels(lat, lng)
        }, () => {
            alert('Gagal mengambil lokasi')
        })
    }

    // LOAD HOTEL
    function loadNearbyHotels(lat, lng) {
        fetch(`/hotels-nearby?lat=${lat}&lng=${lng}`)
            .then(res => res.json())
            .then(hotels => {
                hotelMarkers.clearLayers()

                hotels.forEach(hotel => {
                    L.marker([hotel.latitude, hotel.longitude])
                        .addTo(hotelMarkers)
                        .bindPopup(`
                            <strong>${hotel.name}</strong><br>
                            Rp ${hotel.min_price}
                        `)
                })
            })
    }
})
</script><script>
document.addEventListener('DOMContentLoaded', () => {

    const map = L.map('map', { scrollWheelZoom: false })
        .setView([-6.2, 106.8], 11)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map)

    let userMarker
    let hotelMarkers = L.layerGroup().addTo(map)

    const nearbyContainer = document.getElementById('nearbyHotels')

    // KLIK MAP
    map.on('click', e => {
        const { lat, lng } = e.latlng

        if (userMarker) userMarker.setLatLng(e.latlng)
        else userMarker = L.marker(e.latlng).addTo(map)

        loadNearbyHotels(lat, lng)
    })

    // LOKASI SAYA
    document.getElementById('myLocation').onclick = () => {
        navigator.geolocation.getCurrentPosition(pos => {
            const lat = pos.coords.latitude
            const lng = pos.coords.longitude

            map.setView([lat, lng], 13)

            if (userMarker) userMarker.setLatLng([lat, lng])
            else userMarker = L.marker([lat, lng]).addTo(map)

            loadNearbyHotels(lat, lng)
        })
    }

    function loadNearbyHotels(lat, lng) {
        nearbyContainer.innerHTML = `
            <p class="text-gray-400 col-span-full">Memuat hotel terdekat...</p>
        `

        fetch(`/hotels-nearby?lat=${lat}&lng=${lng}`)
            .then(res => res.json())
            .then(hotels => {
                hotelMarkers.clearLayers()
                nearbyContainer.innerHTML = ''

                if (hotels.length === 0) {
                    nearbyContainer.innerHTML = `
                        <p class="text-gray-400 col-span-full">
                            Tidak ada hotel di sekitar lokasi ini.
                        </p>
                    `
                    return
                }

                hotels.forEach(hotel => {

                    // MARKER
                    L.marker([hotel.latitude, hotel.longitude])
                        .addTo(hotelMarkers)
                        .bindPopup(`<strong>${hotel.name}</strong><br>Rp ${hotel.min_price}`)

                    // CARD LIST
                    nearbyContainer.innerHTML += `
                        <a href="/hotels/${hotel.id}"
                           class="bg-white rounded-2xl shadow hover:shadow-lg transition p-5">

                            <h5 class="font-semibold text-[#134662]">
                                ${hotel.name}
                            </h5>

                            <p class="text-sm text-gray-500 mt-1">
                                ${hotel.city ?? ''}
                            </p>

                            <p class="mt-3 font-bold text-[#134662]">
                                Rp ${hotel.min_price}
                                <span class="text-sm font-normal text-gray-400">/ malam</span>
                            </p>
                        </a>
                    `
                })
            })
    }
})
</script>

@endsection