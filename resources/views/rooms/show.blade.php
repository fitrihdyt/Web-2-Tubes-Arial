@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10 space-y-10">

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
                            <svg class="w-5 h-5 text-gray-400"
                                 fill="none" stroke="currentColor" stroke-width="1.7"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16 14a4 4 0 10-8 0m8 0a4 4 0 01-8 0m8 0v6H8v-6"/>
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

</div>
@endsection
