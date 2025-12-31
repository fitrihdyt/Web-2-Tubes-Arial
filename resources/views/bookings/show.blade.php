@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4">

    {{-- TITLE --}}
    <h1 class="text-3xl font-bold mb-8 text-slate-800">
        Detail Booking
    </h1>

    <div class="bg-white rounded-3xl shadow border border-slate-200 overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-slate-800 p-6 text-white">
            <p class="text-sm text-slate-300">Hotel</p>
            <h2 class="text-2xl font-semibold">
                {{ $booking->room->hotel->name }}
            </h2>

            <p class="mt-1 text-sm text-slate-300">
                Kamar: <span class="text-white font-medium">{{ $booking->room->name }}</span>
            </p>
        </div>

        {{-- CONTENT --}}
        <div class="p-6 space-y-6">

            {{-- INFO GRID --}}
            <div class="grid sm:grid-cols-2 gap-6 text-sm">

                <div>
                    <p class="text-slate-500">Check-in</p>
                    <p class="font-semibold text-slate-800">
                        {{ \Carbon\Carbon::parse($booking->check_in)->translatedFormat('d F Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-500">Check-out</p>
                    <p class="font-semibold text-slate-800">
                        {{ \Carbon\Carbon::parse($booking->check_out)->translatedFormat('d F Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-500">Jumlah Kamar</p>
                    <p class="font-semibold text-slate-800">
                        {{ $booking->qty }} kamar
                    </p>
                </div>

                <div>
                    <p class="text-slate-500">Status Booking</p>

                    @if($booking->status === 'pending')
                        <span class="inline-block px-4 py-1 rounded-full
                                     bg-yellow-100 text-yellow-700 font-semibold">
                            Menunggu Pembayaran
                        </span>
                    @elseif($booking->status === 'paid')
                        <span class="inline-block px-4 py-1 rounded-full
                                     bg-emerald-100 text-emerald-700 font-semibold">
                            Lunas
                        </span>
                    @else
                        <span class="inline-block px-4 py-1 rounded-full
                                     bg-slate-100 text-slate-700 font-semibold">
                            {{ ucfirst($booking->status) }}
                        </span>
                    @endif
                </div>

            </div>

            {{-- TOTAL --}}
            <div class="border-t border-slate-200 pt-6 flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Total Pembayaran</p>
                    <p class="text-2xl font-bold text-slate-900">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </p>
                </div>

                {{-- ACTION --}}
                @if ($booking->status === 'pending')
                    <form action="{{ route('bookings.pay', $booking) }}" method="POST">
                        @csrf
                        <button
                            class="bg-slate-800 hover:bg-slate-900
                                   text-white px-6 py-3 rounded-xl
                                   font-semibold shadow">
                            Bayar Sekarang
                        </button>
                    </form>
                @else
                    <span class="text-emerald-600 font-semibold">
                        ✔ Sudah Dibayar
                    </span>
                @endif
            </div>

        </div>
    </div>

</div>
@endsection
