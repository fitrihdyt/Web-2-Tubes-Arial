@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <div class="flex gap-6 mb-6">
        <div class="w-64 h-40 bg-gray-100 rounded-lg overflow-hidden">
            @if($room->thumbnail)
                <img src="{{ asset('storage/' . $room->thumbnail) }}"
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    No Image
                </div>
            @endif
        </div>

        <div class="flex-1">
            <h1 class="text-3xl font-bold">{{ $room->name }}</h1>

            <p class="text-gray-500 mt-1">
                Hotel:
                <a href="{{ route('hotels.show', $room->hotel) }}"
                   class="text-blue-600 hover:underline">
                    {{ $room->hotel->name }}
                </a>
            </p>

            <p class="text-xl font-semibold text-blue-600 mt-3">
                Rp {{ number_format($room->price) }} / malam
            </p>

            <p class="text-gray-700 mt-2">
                Kapasitas: {{ $room->capacity }} orang
            </p>

            <p class="text-gray-700">
                Stok tersedia: {{ $room->stock }}
            </p>
        </div>
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Deskripsi Room</h2>
        <p class="text-gray-700">
            {{ $room->description ?? 'Tidak ada deskripsi.' }}
        </p>
    </div>

    <div class="flex gap-4">
        @auth
            @if(auth()->user()->role === 'user')
                <a href="{{ route('bookings.create', $room) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                    Book Sekarang
                </a>
            @endif

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('rooms.edit', $room) }}"
                   class="bg-yellow-500 text-white px-6 py-3 rounded-lg">
                    Edit Room
                </a>
            @endif
        @else
            <a href="{{ route('login') }}"
               class="bg-blue-600 text-white px-6 py-3 rounded-lg">
                Login untuk Booking
            </a>
        @endauth
    </div>

</div>
@endsection
