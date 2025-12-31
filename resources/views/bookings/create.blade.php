@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">
    Booking Room: {{ $room->name }}
</h1>

<div class="bg-white p-6 rounded-xl shadow space-y-4">
    <p><strong>Hotel:</strong> {{ $room->hotel->name }}</p>
    <p><strong>Harga / malam:</strong> Rp {{ number_format($room->price) }}</p>
    <p><strong>Total Stok Kamar:</strong> {{ $room->stock }}</p>

    {{-- ERROR MESSAGE --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-4">
        @csrf

        <input type="hidden" name="room_id" value="{{ $room->id }}">

        {{-- CHECK IN --}}
        <div>
            <label class="block font-medium">Check-in</label>
            <input type="date"
                   name="check_in"
                   value="{{ old('check_in') }}"
                   class="w-full border rounded p-2"
                   required>
        </div>

        {{-- CHECK OUT --}}
        <div>
            <label class="block font-medium">Check-out</label>
            <input type="date"
                   name="check_out"
                   value="{{ old('check_out') }}"
                   class="w-full border rounded p-2"
                   required>
        </div>

        {{-- QTY --}}
        <div>
            <label class="block font-medium">Jumlah Kamar</label>
            <input type="number"
                   name="qty"
                   min="1"
                   max="{{ $room->stock }}"
                   value="{{ old('qty', 1) }}"
                   class="w-full border rounded p-2"
                   required>
        </div>

        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Konfirmasi Booking
        </button>
    </form>
</div>
@endsection
