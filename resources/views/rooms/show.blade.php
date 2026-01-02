@extends('layouts.app')

@section('content')
@php
    $images = [];

    if (is_array($room->images)) {
        $images = $room->images;
    } elseif (is_string($room->images)) {
        $decoded = json_decode($room->images, true);
        $images = is_array($decoded) ? $decoded : [];
    }
@endphp

<div class="max-w-6xl mx-auto px-6 py-10 space-y-10">
    {{-- BACK BUTTON --}}
    <div class="mb-4">
        <a href="{{ route('hotels.show', $room->hotel) }}"
        class="inline-flex items-center gap-2
                text-sm font-medium text-gray-600
                hover:text-[#134662] transition">

            <svg class="w-5 h-5"
                fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 18l-6-6 6-6"/>
            </svg>

            Kembali ke Hotel
        </a>
    </div>

    {{-- HEADER CARD --}}
    <div class="bg-white rounded-3xl shadow overflow-hidden">

        <div class="grid md:grid-cols-2 gap-8 p-8">

            {{-- IMAGE --}}
            <div class="rounded-2xl overflow-hidden bg-gray-100 h-[280px]">
                @if($room->thumbnail)
                    <img src="{{ asset('storage/' . $room->thumbnail) }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                        No Image Available
                    </div>
                @endif
            </div>

            {{-- INFO --}}
            <div class="flex flex-col justify-between">

                <div class="space-y-4">

                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            {{ $room->name }}
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">
                            Hotel
                            <a href="{{ route('hotels.show', $room->hotel) }}"
                               class="text-[#134662] font-medium hover:underline">
                                {{ $room->hotel->name }}
                            </a>
                        </p>
                    </div>

                    {{-- PRICE --}}
                    <div class="pt-2">
                        <p class="text-3xl font-bold text-[#ff5a1f]">
                            Rp {{ number_format($room->price, 0, ',', '.') }}
                            <span class="text-sm font-normal text-gray-400">
                                / malam
                            </span>
                        </p>
                    </div>

                    {{-- META --}}
                    <div class="flex flex-wrap gap-8 pt-2 text-sm text-gray-600">

                        {{-- CAPACITY --}}
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400"
                                fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5v-2a4 4 0 00-4-4h-1
                                        M9 20H4v-2a4 4 0 014-4h1
                                        m4-4a4 4 0 100-8 4 4 0 000 8z"/>
                            </svg>
                            Kapasitas {{ $room->capacity }} orang
                        </div>

                        

                            @auth
                                @if(in_array(auth()->user()->role, ['hotel_admin', 'super_admin']))
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400"
                                            fill="none" stroke="currentColor" stroke-width="1.7"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 7h18M3 12h18M3 17h18"/>
                                        </svg>
                                        Stok
                                        <span class="font-semibold
                                            {{ $room->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $room->stock > 0 ? $room->stock : 'Habis' }}
                                        </span>
                                    </div>
                                @endif
                            @endauth


                    </div>
                </div>

                {{-- ACTION --}}
                <div class="pt-8 flex flex-wrap gap-4">

                    @auth
                        @if(auth()->user()->role === 'user' && $room->stock > 0)
                            <a href="{{ route('bookings.create', $room) }}"
                               class="bg-[#ff5a1f] hover:bg-[#e64a19]
                                      text-white px-8 py-4 rounded-xl
                                      text-sm font-semibold shadow transition">
                                Booking Sekarang
                            </a>
                        @endif

                        @if(auth()->user()->role === 'hotel_admin')
                            <a href="{{ route('rooms.edit', $room) }}"
                               class="border border-gray-300 hover:bg-gray-50
                                      text-gray-700 px-8 py-4 rounded-xl
                                      text-sm font-semibold transition">
                                Edit Kamar
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="bg-[#ff5a1f] hover:bg-[#e64a19]
                                  text-white px-8 py-4 rounded-xl
                                  text-sm font-semibold shadow transition">
                            Login untuk Booking
                        </a>
                    @endauth

                </div>

            </div>
        </div>

        {{-- DESCRIPTION --}}
        <div class="border-t px-8 py-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">
                Deskripsi Kamar
            </h2>
            <p class="text-gray-600 leading-7 tracking-wide max-w-3xl text-justify">
                {{ $room->description ?? 'Tidak ada deskripsi untuk kamar ini.' }}
            </p>
        </div>

    </div>
@if(count($images) > 0)
<div class="bg-white rounded-3xl shadow p-8">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">
        Galeri Kamar
    </h2>

    <div class="grid grid-cols-4 gap-4">
        @foreach($images as $i => $image)
            @if($i < 3)
                <button onclick="openRoomGallery()"
                        class="rounded-xl overflow-hidden aspect-square">
                    <img src="{{ asset('storage/'.$image) }}"
                         class="w-full h-full object-cover">
                </button>
            @endif
        @endforeach

        @if(count($images) > 3)
        <button onclick="openRoomGallery()"
                class="relative rounded-xl overflow-hidden aspect-square">
            <img src="{{ asset('storage/'.$images[0]) }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                <span class="text-white text-sm font-semibold">
                    Lihat Semua
                </span>
            </div>
        </button>
        @endif
    </div>
</div>
@endif


</div>
@if(count($images) > 0)
<div id="roomGalleryModal"
     class="fixed inset-0 bg-black/80 z-[9999] hidden">

    <div class="fixed top-4 left-4">
        <button onclick="closeRoomGallery()"
                class="bg-black/60 p-2 rounded-full text-white">
            <svg class="w-6 h-6"
                 fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 18l-6-6 6-6"/>
            </svg>
        </button>
    </div>

    <div class="max-w-6xl mx-auto px-6 pt-24
                grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($images as $image)
            <img src="{{ asset('storage/'.$image) }}"
                 class="w-full h-[200px] object-cover rounded-xl">
        @endforeach
    </div>
</div>
@endif


@endsection
@push('scripts')
<script>
function openRoomGallery() {
    document.getElementById('roomGalleryModal')
        .classList.remove('hidden')
    document.body.classList.add('overflow-hidden')
}

function closeRoomGallery() {
    document.getElementById('roomGalleryModal')
        .classList.add('hidden')
    document.body.classList.remove('overflow-hidden')
}
</script>
@endpush
