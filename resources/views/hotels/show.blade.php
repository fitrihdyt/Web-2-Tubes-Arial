@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold">{{ $hotel->name }}</h1>
    <p class="text-gray-500">{{ $hotel->city }}</p>

    <div class="text-yellow-500 mt-1">
        {{ str_repeat('★', $hotel->star) }}
    </div>
</div>

@if($hotel->thumbnail)
    <img src="{{ asset('storage/'.$hotel->thumbnail) }}"
         class="w-full h-72 object-cover rounded-xl mb-6">
@endif

<p class="mb-6 text-gray-700">
    {{ $hotel->description }}
</p>

{{-- ======================= --}}
{{-- FASILITAS HOTEL (FULL) --}}
{{-- ======================= --}}
@if($hotel->facilities && $hotel->facilities->count())
    <hr class="mb-6">

    <h2 class="text-2xl font-semibold mb-4">Fasilitas Hotel</h2>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-6">
        @foreach($hotel->facilities as $facility)
            <div class="bg-gray-100 px-3 py-2 rounded text-sm text-gray-700">
                {{ $facility->pivot->custom_name ?? $facility->name }}
            </div>
        @endforeach
    </div>
@endif

<hr class="mb-6">

<h2 class="text-2xl font-semibold mb-4">Daftar Room</h2>

@if($hotel->rooms->isEmpty())
    <div class="bg-white p-4 rounded shadow">
        Belum ada room tersedia.
    </div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
@foreach($hotel->rooms as $room)
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="h-40 bg-gray-100">
            @if($room->thumbnail)
                <img src="{{ asset('storage/'.$room->thumbnail) }}"
                     class="w-full h-full object-cover">
            @endif
        </div>

        <div class="p-4">
            <h3 class="text-lg font-semibold">{{ $room->name }}</h3>

            <p class="text-sm text-gray-500">
                Kapasitas: {{ $room->capacity }} orang
            </p>

            <p class="mt-1 font-semibold text-blue-600">
                Rp {{ number_format($room->price) }} / malam
            </p>

            <p class="text-sm mt-1">
                Stok tersedia: {{ $room->stock }}
            </p>

            @auth
                @if($room->stock > 0)
                    <a href="{{ route('bookings.create', $room) }}"
                       class="inline-block mt-4 bg-green-600 text-white px-4 py-2 rounded-lg">
                        Booking
                    </a>
                @else
                    <span class="inline-block mt-4 text-red-500">
                        Kamar penuh
                    </span>
                @endif
            @else
                <a href="{{ route('login') }}"
                   class="inline-block mt-4 text-blue-600 underline">
                    Login untuk booking
                </a>
            @endauth
        </div>

    </div>
@endforeach
</div>
@endif
@endsection
