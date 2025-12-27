@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Booking Saya</h1>

@if ($bookings->isEmpty())
    <div class="bg-white p-6 rounded">
        Belum ada booking.
    </div>
@else
<table class="w-full bg-white rounded shadow">
    <thead>
        <tr>
            <th>Hotel</th>
            <th>Room</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bookings as $booking)
        <tr>
            <td>{{ $booking->room->hotel->name }}</td>
            <td>{{ $booking->room->name }}</td>
            <td>{{ $booking->check_in }} → {{ $booking->check_out }}</td>
            <td>Rp {{ number_format($booking->total_price) }}</td>
            <td>{{ ucfirst($booking->status) }}</td>
            <td>
                <a href="{{ route('bookings.show', $booking) }}">Detail</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection
