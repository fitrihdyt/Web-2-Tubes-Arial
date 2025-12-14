@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Detail Booking</h1>

<div class="bg-white p-6 rounded-xl shadow space-y-3">
    <p><strong>Hotel:</strong> {{ $booking->room->hotel->name }}</p>
    <p><strong>Room:</strong> {{ $booking->room->name }}</p>
    <p><strong>Check-in:</strong> {{ $booking->check_in }}</p>
    <p><strong>Check-out:</strong> {{ $booking->check_out }}</p>
    <p><strong>Total:</strong> Rp {{ number_format($booking->total_price) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>

    @if ($booking->status === 'pending')
        <a href="#"
           class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg mt-4">
            Bayar Sekarang
        </a>
    @endif
</div>
@endsection
