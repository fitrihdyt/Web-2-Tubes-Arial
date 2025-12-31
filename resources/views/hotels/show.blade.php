@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4">

    {{-- HERO --}}
    <div class="grid md:grid-cols-3 gap-6 mb-10">

        {{-- IMAGE --}}
        <div class="md:col-span-2">
            <img src="{{ $hotel->thumbnail ? asset('storage/'.$hotel->thumbnail) : 'https://via.placeholder.com/800x400' }}"
                 class="w-full h-[360px] object-cover rounded-3xl shadow">
        </div>

        {{-- INFO --}}
        <div class="bg-white rounded-3xl shadow p-6 flex flex-col justify-between">

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    {{ $hotel->name }}
                </h1>

                {{-- STAR --}}
                <div class="flex items-center gap-1 text-yellow-500 text-sm mt-2">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $hotel->star)
                            ★
                        @else
                            <span class="text-gray-300">★</span>
                        @endif
                    @endfor
                    <span class="text-gray-400 ml-2 text-xs">
                        {{ $hotel->star }}/5
                    </span>
                </div>

                {{-- LOCATION --}}
                <div class="flex items-center gap-1.5 text-sm text-gray-500 mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 text-gray-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5
                                9 6.343 9 8s1.343 3 3 3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.5 8c0 7-7.5 11-7.5 11S4.5 15 4.5 8
                                a7.5 7.5 0 1115 0z"/>
                    </svg>
                    <span>{{ $hotel->city }}</span>
                </div>

                {{-- DESCRIPTION --}}
                <p class="text-sm text-gray-600 mt-4 leading-relaxed">
                    {{ $hotel->description }}
                </p>
            </div>

            {{-- PRICE --}}
            @if($hotel->rooms->count())
                <div class="mt-6">
                    <p class="text-sm text-gray-500">Harga mulai dari</p>
                    <p class="text-2xl font-bold text-[#ff5a1f]">
                        Rp {{ number_format($hotel->rooms->min('price'), 0, ',', '.') }}
                        <span class="text-sm text-gray-400 font-normal">/ malam</span>
                    </p>
                </div>
            @endif

        </div>

    </div>

    {{-- FASILITAS --}}
    @if($hotel->facilities->count())
        <div class="bg-white rounded-3xl shadow p-6 mb-10">
            <h2 class="text-xl font-semibold mb-4">Fasilitas Hotel</h2>

            <div class="flex flex-wrap gap-3">
                @foreach($hotel->facilities as $facility)
                    <span class="bg-[#eef6f8] text-[#134662] px-4 py-2 rounded-full text-sm">
                        {{ $facility->pivot->custom_name ?? $facility->name }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ROOMS --}}
    <h2 class="text-2xl font-bold mb-6">Pilihan Kamar</h2>

    @if($hotel->rooms->isEmpty())
        <div class="bg-white p-6 rounded-2xl shadow">
            Belum ada kamar tersedia.
        </div>
    @else
        <div class="grid md:grid-cols-2 gap-6">
        @foreach($hotel->rooms as $room)
            <div class="bg-white rounded-3xl shadow hover:shadow-lg transition overflow-hidden">

                <div class="h-44 bg-gray-100">
                    @if($room->thumbnail)
                        <img src="{{ asset('storage/'.$room->thumbnail) }}"
                             class="w-full h-full object-cover">
                    @endif
                </div>

                <div class="p-6 flex flex-col justify-between h-full">

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $room->name }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Kapasitas {{ $room->capacity }} orang
                        </p>

                        <p class="text-xl font-bold text-[#ff5a1f] mt-3">
                            Rp {{ number_format($room->price, 0, ',', '.') }}
                            <span class="text-sm text-gray-400 font-normal">/ malam</span>
                        </p>

                        <p class="text-sm mt-1 text-gray-500">
                            Stok: {{ $room->stock }}
                        </p>
                    </div>

                    {{-- ACTION --}}
                    <div class="mt-5">
                        @auth
                            @if($room->stock > 0)
                                <a href="{{ route('bookings.create', $room) }}"
                                   class="block text-center bg-[#ff5a1f] hover:bg-[#e64a19]
                                          text-white py-3 rounded-xl font-semibold">
                                    Booking Sekarang
                                </a>
                            @else
                                <div class="text-center text-red-500 font-semibold">
                                    Kamar Penuh
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                               class="block text-center text-blue-600 underline">
                                Login untuk booking
                            </a>
                        @endauth
                    </div>

                </div>
            </div>
        @endforeach
        </div>
    @endif

</div>
@endsection
