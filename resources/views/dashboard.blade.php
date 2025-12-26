@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Temukan Penginapan Terbaik</h1>
        <p class="text-gray-500 mt-2">Jelajahi ribuan hotel dengan harga terbaik di seluruh Indonesia.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <div class="w-full lg:w-2/3">
            
            <form method="GET" action="{{ route('dashboard') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8 container">
                <div class="relative mb-6">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Mau menginap di mana?" 
                           class="w-full pl-10 pr-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Rating Bintang</label>
                        <div class="flex flex-wrap gap-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="star[]" value="{{ $i }}" class="hidden peer" {{ in_array($i, request('star', [])) ? 'checked' : '' }}>
                                    <span class="px-3 py-1 border rounded-full text-sm peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition hover:bg-gray-50">
                                        {{ $i }}★
                                    </span>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Range Harga</label>
                        <select name="price" class="w-full bg-gray-50 border-none rounded-xl py-2.5 focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Harga</option>
                            <option value="0-500" {{ request('price')=='0-500'?'selected':'' }}>Under Rp 500k</option>
                            <option value="500-1000" {{ request('price')=='500-1000'?'selected':'' }}>Rp 500k - 1M</option>
                            <option value="1000+" {{ request('price')=='1000+'?'selected':'' }}>Above Rp 1M</option>
                        </select>
                    </div>

                    <div class="flex flex-col justify-end">
                        <button class="bg-blue-600 hover:bg-blue-300 text-white font-bold py-2.5 rounded-xl transition shadow-lg shadow-blue-100">
                            Terapkan Filter
                        </button>
                    </div>
                </div>

                <input type="hidden" name="lat" id="lat" value="{{ request('lat') }}">
                <input type="hidden" name="lng" id="lng" value="{{ request('lng') }}">
            </form>

            @if($hotels->isEmpty())
                <div class="text-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed">
                    <p class="text-gray-400">Oops! Tidak ada hotel yang sesuai kriteria Anda.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($hotels as $hotel)
                    <a href="{{ route('hotels.show', $hotel) }}" class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300">
                        <div class="relative h-52">
                            <img src="{{ $hotel->thumbnail ? asset('storage/'.$hotel->thumbnail) : 'https://via.placeholder.com/400x300?text=No+Image' }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-2 py-1 rounded-lg text-xs font-bold shadow-sm">
                                <span class="text-yellow-500">★</span> {{ $hotel->star }}
                            </div>
                        </div>
                        <div class="p-5">
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">{{ $hotel->city }}</p>
                            <h2 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-blue-600 transition">{{ $hotel->name }}</h2>
                            <div class="flex items-center justify-between mt-4">
                                <div>
                                    <p class="text-gray-400 text-xs">Mulai dari</p>
                                    <p class="text-lg font-extrabold text-gray-900">Rp {{ number_format($hotel->rooms_min_price ?? 0, 0, ',', '.') }}<span class="text-sm font-normal text-gray-500">/malam</span></p>
                                </div>
                                <span class="bg-gray-100 p-2 rounded-full group-hover:bg-blue-600 group-hover:text-white transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="w-full lg:w-1/3">
            <div class="sticky top-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 border-b border-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">Cari via Area</h3>
                        <span class="text-xs text-gray-400 italic">Klik peta untuk koordinat</span>
                    </div>
                    <div id="map" class="w-full h-[500px] z-0"></div>
                    <div class="p-4 bg-blue-50">
                        <p class="text-xs text-blue-700 leading-relaxed">
                            <strong>Tips:</strong> Anda bisa mencari hotel berdasarkan radius lokasi yang Anda pilih di peta.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    input:focus, select:focus { outline: none !important; }
    #map { cursor: crosshair; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('map', {
        scrollWheelZoom: false 
    }).setView([-6.2, 106.8], 11);

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
        
        console.log("Lokasi dipilih: " + e.latlng.lat + ", " + e.latlng.lng);
    });
});
</script>
@endsection