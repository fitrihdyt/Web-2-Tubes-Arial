@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Detail Booking</h1>

<div class="bg-white p-6 rounded-xl shadow space-y-3">
    <p><strong>Hotel:</strong> {{ $booking->room->hotel->name }}</p>
    <p><strong>Room:</strong> {{ $booking->room->name }}</p>
    <p><strong>Tanggal:</strong> {{ $booking->check_in }} → {{ $booking->check_out }}</p>
    <p><strong>Total:</strong> Rp {{ number_format($booking->total_price) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>

    @if ($booking->status === 'pending')
        <form action="{{ route('bookings.pay', $booking) }}" method="POST">
            @csrf
            <button class="bg-green-600 text-white px-4 py-2 rounded mt-4">
                Bayar Sekarang
            </button>
        </form>
    @endif
</div>
@endsection
