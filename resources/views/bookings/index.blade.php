@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Booking Saya</h1>

@if ($bookings->isEmpty())
    <div class="bg-white p-6 rounded shadow">
        Belum ada booking.
    </div>
@else
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Hotel</th>
                    <th class="p-3">Room</th>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Total</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                <tr class="border-t">
                    <td class="p-3">{{ $booking->room->hotel->name }}</td>
                    <td class="p-3">{{ $booking->room->name }}</td>
                    <td class="p-3">
                        {{ $booking->check_in }} → {{ $booking->check_out }}
                    </td>
                    <td class="p-3">
                        Rp {{ number_format($booking->total_price) }}
                    </td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded text-sm
                            {{ $booking->status === 'paid' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="p-3 flex gap-2">
                        <a href="{{ route('bookings.show', $booking) }}"
                           class="text-blue-600">Detail</a>

                        @if ($booking->status === 'pending')
                        <form action="{{ route('bookings.destroy', $booking) }}"
                              method="POST"
                              onsubmit="return confirm('Batalkan booking?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600">Batalkan</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
