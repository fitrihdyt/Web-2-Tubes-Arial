@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Data Booking Hotel
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $hotel->name }}
            </p>
        </div>

        <a href="{{ route('hotels.show', $hotel) }}"
           class="text-blue-600 hover:underline text-sm font-semibold">
            ← Kembali ke Detail Hotel
        </a>
    </div>

    @if($hotel->bookings->isEmpty())
        <div class="bg-white rounded-3xl shadow p-10 text-center text-gray-500">
            Belum ada booking untuk hotel ini.
        </div>
    @else

        <div class="grid gap-6">
            @foreach($hotel->bookings as $booking)
                <div class="bg-white rounded-3xl shadow p-6">

                    {{-- TOP --}}
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="font-semibold text-gray-800">
                                {{ $booking->user->name ?? 'User' }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ $booking->user->email ?? '-' }}
                            </p>
                        </div>

                        <span class="px-4 py-1.5 rounded-full text-xs font-semibold
                            @if($booking->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($booking->status === 'paid') bg-green-100 text-green-700
                            @elseif($booking->status === 'cancelled') bg-red-100 text-red-700
                            @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>

                    {{-- INFO --}}
                    <div class="grid md:grid-cols-4 gap-4 text-sm">

                        <div>
                            <p class="text-gray-400">Kamar</p>
                            <p class="font-semibold text-gray-700">
                                {{ $booking->room->name ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-400">Check-in</p>
                            <p class="font-semibold text-gray-700">
                                {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-400">Check-out</p>
                            <p class="font-semibold text-gray-700">
                                {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-400">Jumlah Kamar</p>
                            <p class="font-semibold text-gray-700">
                                {{ $booking->qty }}
                            </p>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="mt-6 flex items-center justify-between border-t pt-4">
                        <span class="text-sm text-gray-500">
                            Booking dibuat:
                            {{ $booking->created_at->format('d M Y') }}
                        </span>

                        <span class="text-lg font-bold text-[#ff5a1f]">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </span>
                    </div>

                </div>
            @endforeach
        </div>

    @endif

</div>
@endsection
