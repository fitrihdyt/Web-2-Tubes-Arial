@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-6">Histori Booking</h2>

@forelse ($bookings as $booking)
<div class="bg-white p-6 rounded-lg shadow mb-4">
    <h3 class="font-semibold text-lg">
        {{ $booking->hotel->name }} - {{ $booking->room->name }}
    </h3>

    <p class="text-sm text-gray-600">
        {{ $booking->check_in }} → {{ $booking->check_out }}
    </p>

    {{-- JIKA BELUM DIRATING --}}
    @if (!$booking->rating)
        <form action="{{ route('ratings.store') }}" method="POST" class="mt-4 space-y-2">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

            <select name="rating" class="border rounded px-3 py-2 w-full">
                <option value="">Pilih Rating</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }} ⭐</option>
                @endfor
            </select>

            <textarea name="review"
                placeholder="Tulis ulasan (opsional)"
                class="border rounded px-3 py-2 w-full"></textarea>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Kirim Rating
            </button>
        </form>
    @else
        {{-- SUDAH DIRATING --}}
        <p class="mt-3 text-green-600">
            Rating: {{ $booking->rating->rating }} ⭐
        </p>
        <p class="text-sm text-gray-700">
            "{{ $booking->rating->review }}"
        </p>
    @endif
</div>
@empty
<p class="text-gray-500">Belum ada histori booking.</p>
@endforelse
@endsection
