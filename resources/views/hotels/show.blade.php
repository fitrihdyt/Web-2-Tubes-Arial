@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4">

    <div class="grid md:grid-cols-3 gap-6 mb-10">

        {{-- THUMBNAIL --}}
        <div class="md:col-span-2">
            <img src="{{ $hotel->thumbnail ? asset('storage/'.$hotel->thumbnail) : 'https://via.placeholder.com/800x400' }}"
                 class="w-full h-[360px] object-cover rounded-3xl shadow">
        </div>

        {{-- INFO --}}
        <div class="bg-white rounded-3xl shadow p-6 flex flex-col justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $hotel->name }}</h1>

                {{-- STAR --}}
                <div class="flex items-center gap-1 text-yellow-500 text-sm mt-2">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $hotel->star ? '' : 'text-gray-300' }}">★</span>
                    @endfor
                    <span class="text-gray-400 ml-2 text-xs">{{ $hotel->star }}/5</span>
                </div>

                {{-- LOCATION --}}
                <div class="flex items-center gap-2 text-sm text-gray-500 mt-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5
                                 9 6.343 9 8s1.343 3 3 3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 8c0 7-7.5 11-7.5 11S4.5 15 4.5 8
                                 a7.5 7.5 0 1115 0z"/>
                    </svg>
                    {{ $hotel->city }}
                </div>

                <p class="text-sm text-gray-600 mt-4 leading-relaxed text-justify">
                    {{ $hotel->description }}
                </p>
            </div>

            @if($hotel->rooms->count())
            <div class="mt-6">
                <p class="text-sm text-gray-500">Harga mulai dari</p>
                <p class="text-2xl font-bold text-[#ff5a1f]">
                    Rp {{ number_format($hotel->rooms->min('price'),0,',','.') }}
                    <span class="text-sm text-gray-400 font-normal">/ malam</span>
                </p>
            </div>
            @endif
            @auth
                @if(
                    auth()->user()->role === 'hotel_admin'
                )
                    <div class="mt-6">
                        <a href="{{ route('hotels.bookings', $hotel->id) }}"
                        class="block text-center bg-blue-600 hover:bg-blue-700
                                text-white py-3 rounded-xl font-semibold transition">
                            Lihat Data Booking Hotel
                        </a>
                    </div>
                @endif
            @endauth

        </div>
    </div>

    {{-- GALLERY & FASILITAS --}}
    <div class="grid md:grid-cols-3 gap-6 mb-10">

        {{-- GALLERY --}}
        <div class="md:col-span-2 bg-white rounded-3xl shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Galeri Foto</h2>

            <div class="grid grid-cols-5 gap-4 relative">
                @foreach(($hotel->images ?? []) as $i => $image)
                    @if($i < 4)
                        <button onclick="openGallery()"
                                class="relative z-10 cursor-pointer rounded-xl overflow-hidden aspect-square">
                            <img src="{{ asset('storage/'.$image) }}"
                                 class="w-full h-full object-cover">
                        </button>
                    @endif
                @endforeach

                @if(count($hotel->images ?? []))
                <button onclick="openGallery()"
                        class="relative z-10 cursor-pointer rounded-xl overflow-hidden aspect-square">
                    <img src="{{ asset('storage/'.$hotel->images[0]) }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                        <span class="text-white text-sm font-semibold">Lihat Semua</span>
                    </div>
                </button>
                @endif
            </div>
        </div>

        {{-- FASILITAS --}}
        @if($hotel->facilities->count())
        <div class="bg-white rounded-3xl shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Fasilitas</h2>
            <div class="flex flex-wrap gap-3">
                @foreach($hotel->facilities as $facility)
                    <span class="bg-[#eef6f8] text-[#134662] px-4 py-2 rounded-full text-sm">
                        {{ $facility->pivot->custom_name ?? $facility->name }}
                    </span>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- ROOMS & MAP --}}
    <div class="grid md:grid-cols-3 gap-6 mb-16">

        {{-- ROOMS --}}
        <div class="md:col-span-2 space-y-4">

            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold">Pilihan Kamar</h2>

                @auth
                    @if(auth()->user()->role === 'hotel_admin')
                        <a href="{{ route('rooms.create', ['hotel' => $hotel->id]) }}"
                           class="px-5 py-2 rounded-xl bg-[#134662]
                                  text-white text-sm font-semibold
                                  hover:bg-[#0f3a4e] transition">
                            + Tambah Kamar
                        </a>
                    @endif
                @endauth
            </div>

            @foreach($hotel->rooms as $room)
            <div
                onclick="window.location='{{ route('rooms.show', $room) }}'"
                class="bg-white rounded-3xl shadow flex overflow-hidden min-h-[200px]
                    cursor-pointer transition
                    hover:shadow-xl hover:-translate-y-0.5">

                {{-- THUMB --}}
                <div class="w-56">
                    <img src="{{ asset('storage/'.$room->thumbnail) }}"
                        class="w-full h-full object-cover">
                </div>

                {{-- CONTENT --}}
                <div class="flex-1 p-6 flex flex-col justify-between">

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $room->name }}
                        </h3>

                        <div class="flex items-center gap-4 mt-3 text-sm text-gray-600">

                            {{-- CAPACITY --}}
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400"
                                    fill="none" stroke="currentColor" stroke-width="1.8"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 20h5v-2a4 4 0 00-4-4h-1
                                            M9 20H4v-2a4 4 0 014-4h1
                                            m4-4a4 4 0 100-8 4 4 0 000 8z"/>
                                </svg>
                                {{ $room->capacity }} orang
                            </div>

                            {{-- STOCK (ADMIN ONLY) --}}
                            @auth
                                @if(auth()->user()->role === 'hotel_admin')
                                    <div class="flex items-center gap-1">
                                        <svg class="w-5 h-5 text-gray-400"
                                                        fill="none" stroke="currentColor" stroke-width="1.7"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3 7h18M3 12h18M3 17h18"/>
                                                    </svg>
                                        <span class="font-semibold
                                                        {{ $room->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                        {{ $room->stock > 0 ? $room->stock : 'Habis' }}
                                                    </span>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>

                    {{-- ADMIN ACTION --}}
                    @auth
                        @if(auth()->user()->role === 'hotel_admin')
                            <div class="flex gap-4 text-xs">

                                <a href="{{ route('rooms.edit', $room) }}"
                                onclick="event.stopPropagation()"
                                class="text-yellow-600 hover:underline">
                                    Edit
                                </a>

                                <form action="{{ route('rooms.destroy', $room) }}"
                                    method="POST"
                                    onsubmit="event.stopPropagation(); return confirm('Hapus room ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        @endif
                    @endauth

                </div>

                {{-- PRICE --}}
                <div class="w-[220px] bg-gray-50 px-6 py-4
                            flex flex-col justify-center items-end border-l">
                    <p class="text-xl font-bold text-[#ff5a1f]">
                        Rp {{ number_format($room->price,0,',','.') }}
                    </p>
                    <p class="text-xs text-gray-400">/ malam</p>
                </div>
            </div>

            @endforeach
        </div>

        {{-- MAP --}}
        @if($hotel->latitude && $hotel->longitude)
        <div class="bg-white rounded-3xl shadow p-6 flex flex-col">
            <h2 class="text-xl font-semibold mb-4">Lokasi Hotel</h2>
            <div id="hotelMap"
                 class="flex-1 rounded-2xl overflow-hidden border"></div>
        </div>
        @endif
    </div>
</div>

<div id="galleryModal"
     class="fixed inset-0 bg-black/80 z-[9999] hidden">

    <div class="fixed top-4 left-4">
        <button onclick="closeGallery()"
                class="bg-black/60 p-2 rounded-full text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 18l-6-6 6-6"/>
            </svg>
        </button>
    </div>

    <div class="max-w-6xl mx-auto px-6 pt-24 grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($hotel->images ?? [] as $image)
            <img src="{{ asset('storage/'.$image) }}"
                 class="w-full h-[200px] object-cover rounded-xl">
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
let hotelMapInstance = null

function openGallery() {
    document.getElementById('galleryModal').classList.remove('hidden')
    document.body.classList.add('overflow-hidden')
}

function closeGallery() {
    document.getElementById('galleryModal').classList.add('hidden')
    document.body.classList.remove('overflow-hidden')
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lat = @json($hotel->latitude);
    const lng = @json($hotel->longitude);

    if (!lat || !lng) return;

    const map = L.map('hotelMap', {
        scrollWheelZoom: false
    }).setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    L.marker([lat, lng])
        .addTo(map)
        .bindPopup(`<b>{{ $hotel->name }}</b><br>{{ $hotel->city }}`);
});
</script>
@endpush
