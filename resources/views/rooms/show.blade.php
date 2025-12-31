@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 space-y-8">

    {{-- CARD --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        {{-- TOP --}}
        <div class="flex flex-col md:flex-row gap-8 p-6">

            {{-- IMAGE --}}
            <div class="w-full md:w-[360px] h-[240px]
                        bg-gray-100 rounded-xl overflow-hidden shrink-0">
                @if($room->thumbnail)
                    <img src="{{ asset('storage/' . $room->thumbnail) }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        No Image
                    </div>
                @endif
            </div>

            {{-- INFO --}}
            <div class="flex-1 flex flex-col justify-between">

                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        {{ $room->name }}
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Hotel
                        <a href="{{ route('hotels.show', $room->hotel) }}"
                           class="text-[#134662] hover:underline font-medium">
                            {{ $room->hotel->name }}
                        </a>
                    </p>

                    {{-- PRICE --}}
                    <p class="text-2xl font-bold text-[#ff5a1f] mt-4">
                        Rp {{ number_format($room->price, 0, ',', '.') }}
                        <span class="text-sm font-normal text-gray-400">
                            / malam
                        </span>
                    </p>

                    {{-- META --}}
                    <div class="flex flex-wrap gap-6 mt-4 text-sm text-gray-600">

                        <div class="flex items-center gap-1">
                            {{-- USER ICON --}}
                            <svg class="w-4 h-4 text-gray-400"
                                 fill="none" stroke="currentColor" stroke-width="1.8"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8z"/>
                            </svg>
                            Kapasitas {{ $room->capacity }} orang
                        </div>

                        <div class="flex items-center gap-1">
                            {{-- BOX ICON --}}
                            <svg class="w-4 h-4 text-gray-400"
                                 fill="none" stroke="currentColor" stroke-width="1.8"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6M4 13h16v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6z"/>
                            </svg>
                            Stok
                            <span class="font-semibold
                                {{ $room->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $room->stock > 0 ? $room->stock : 'Habis' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- ACTION --}}
                <div class="flex flex-wrap gap-4 mt-6">

                    @auth
                        @if(auth()->user()->role === 'user')
                            <a href="{{ route('bookings.create', $room) }}"
                               class="bg-[#ff5a1f] hover:bg-[#e64a19]
                                      text-white px-6 py-3 rounded-xl
                                      text-sm font-semibold shadow">
                                Book Sekarang
                            </a>
                        @endif

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('rooms.edit', $room) }}"
                               class="bg-yellow-500 hover:bg-yellow-600
                                      text-white px-6 py-3 rounded-xl
                                      text-sm font-semibold shadow">
                                Edit Room
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="bg-[#ff5a1f] hover:bg-[#e64a19]
                                  text-white px-6 py-3 rounded-xl
                                  text-sm font-semibold shadow">
                            Login untuk Booking
                        </a>
                    @endauth

                </div>
            </div>
        </div>

        {{-- DESCRIPTION --}}
        <div class="border-t px-6 py-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">
                Deskripsi Room
            </h2>
            <p class="text-gray-600 leading-relaxed">
                {{ $room->description ?? 'Tidak ada deskripsi.' }}
            </p>
        </div>

    </div>

</div>
@endsection
