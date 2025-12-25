@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">
    Booking Room: {{ $room->name }}
</h1>

<div class="bg-white p-6 rounded-xl shadow space-y-4">
    <p><strong>Hotel:</strong> {{ $room->hotel->name }}</p>
    <p><strong>Harga / malam:</strong> Rp {{ number_format($room->price) }}</p>

    <form action="{{ route('bookings.store') }}" method="POST">
        @csrf
        <input type="hidden" name="room_id" value="{{ $room->id }}">

        <div>
            <label>Check-in</label>
            <input type="date" name="check_in" class="w-full border p-2" required>
        </div>

        <div>
            <label>Check-out</label>
            <input type="date" name="check_out" class="w-full border p-2" required>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Konfirmasi Booking
        </button>
    </form>
</div>
@endsection
