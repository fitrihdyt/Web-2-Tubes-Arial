@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12" style="font-family: 'Inter', sans-serif;">
    <div class="mb-10">
        <h1 class="text-4xl md:text-5xl font-bold text-[#2D5356] tracking-tight" style="font-family: 'Playfair Display', serif;">
            Temukan Penginapan Terbaik
        </h1>
        <p class="text-gray-500 mt-3 text-lg">Jelajahi ribuan hotel dengan harga terbaik di seluruh Indonesia.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-10">
        
        <div class="w-full lg:w-2/3">
            
            <form method="GET" action="{{ route('dashboard') }}" class="bg-white p-8 rounded-[2rem] shadow-xl shadow-gray-100/50 border border-gray-100 mb-10 container">
                <div class="relative mb-8">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-[#2D5356]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Mau menginap di mana?" 
                           class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#2D5356] transition text-gray-700">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <label class="block text-sm font-bold text-[#2D5356] mb-3 uppercase tracking-widest">Rating Bintang</label>
                        <div class="flex flex-wrap gap-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="star[]" value="{{ $i }}" class="hidden peer" {{ in_array($i, request('star', [])) ? 'checked' : '' }}>
                                    <span class="px-4 py-1.5 border border-gray-200 rounded-full text-sm font-medium peer-checked:bg-[#2D5356] peer-checked:text-white peer-checked:border-[#2D5356] transition hover:border-[#2D5356] inline-block">
                                        {{ $i }}★
                                    </span>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-[#2D5356] mb-3 uppercase tracking-widest">Range Harga</label>
                        <select name="price" class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 focus:ring-2 focus:ring-[#2D5356] text-gray-700 font-medium">
                            <option value="">Semua Harga</option>
                            <option value="0-500" {{ request('price')=='0-500'?'selected':'' }}>Under Rp 500k</option>
                            <option value="500-1000" {{ request('price')=='500-1000'?'selected':'' }}>Rp 500k - 1M</option>
                            <option value="1000+" {{ request('price')=='1000+'?'selected':'' }}>Above Rp 1M</option>
                        </select>
                    </div>

                    <div class="flex flex-col justify-end">
                        <button class="bg-[#2D5356] hover:bg-[#1e3a3c] text-white font-bold py-3.5 rounded-2xl transition shadow-lg shadow-[#2d5356]/20">
                            Terapkan Filter
                        </button>
                    </div>
                </div>

                <input type="hidden" name="lat" id="lat" value="{{ request('lat') }}">
                <input type="hidden" name="lng" id="lng" value="{{ request('lng') }}">
            </form>

            @if($hotels->isEmpty())
                <div class="text-center py-24 bg-white rounded-[2rem] border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 font-medium text-lg">Oops! Tidak ada hotel yang sesuai kriteria Anda.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($hotels as $hotel)
                    <a href="{{ route('hotels.show', $hotel) }}" class="group bg-white rounded-[2rem] shadow-sm border border-gray-50 overflow-hidden hover:shadow-2xl transition-all duration-500">
                        <div class="relative h-64 overflow-hidden">
                            <img src="{{ $hotel->thumbnail ? asset('storage/'.$hotel->thumbnail) : 'https://via.placeholder.com/400x300?text=No+Image' }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            <div class="absolute top-4 right-4 bg-white/95 backdrop-blur px-3 py-1.5 rounded-xl text-sm font-bold shadow-md">
                                <span class="text-[#C5A47E]">★</span> {{ $hotel->star }}
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-xs font-bold text-[#C5A47E] uppercase tracking-[0.2em] mb-2">{{ $hotel->city }}</p>
                            <h2 class="text-2xl font-bold text-gray-800 mb-4 group-hover:text-[#2D5356] transition">{{ $hotel->name }}</h2>
                            <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-50">
                                <div>
                                    <p class="text-gray-400 text-[10px] uppercase tracking-widest font-bold">Mulai dari</p>
                                    <p class="text-xl font-extrabold text-[#2D5356]">Rp {{ number_format($hotel->rooms_min_price ?? 0, 0, ',', '.') }}<span class="text-sm font-normal text-gray-500">/malam</span></p>
                                </div>
                                <span class="bg-gray-50 p-3 rounded-full group-hover:bg-[#2D5356] group-hover:text-white transition-all duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
            <div class="sticky top-24"> <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-white">
                        <h3 class="font-bold text-[#2D5356] uppercase tracking-wider text-sm">Cari via Area</h3>
                        <span class="text-[10px] text-gray-400 italic">Klik peta untuk koordinat</span>
                    </div>
                    <div id="map" class="w-full h-[500px] z-0"></div>
                    <div class="p-5 bg-[#2D5356]">
                        <p class="text-xs text-white/80 leading-relaxed font-light">
                            <strong class="text-[#C5A47E] font-bold">Tips:</strong> Cari hotel berdasarkan radius lokasi yang Anda pilih di peta.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600;700&display=swap');
    
    input:focus, select:focus { 
        outline: none !important; 
        box-shadow: 0 0 0 3px rgba(45, 83, 86, 0.15) !important;
    }
    #map { cursor: crosshair; }
    
    .leaflet-container {
        font-family: 'Inter', sans-serif;
    }
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
    });
});
</script>
@endsection