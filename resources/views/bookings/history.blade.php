@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Histori Booking</h1>

    @forelse($bookings as $booking)
        <div class="bg-white rounded-lg shadow p-5 mb-4">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold">{{ $booking->hotel->name }}</h2>
                    <p class="text-sm text-gray-600">
                        {{ $booking->check_in }} - {{ $booking->check_out }}
                    </p>
                </div>
                <span class="text-green-600 font-semibold">Selesai</span>
            </div>

            <div class="mt-4">
                @if($booking->rating)
                    <p class="text-yellow-500">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $booking->rating ? '★' : '☆' }}
                        @endfor
                    </p>
                    <p class="text-sm text-gray-600 mt-1">{{ $booking->review }}</p>
                @else
                    <form action="{{ route('booking.rating', $booking->id) }}" method="POST">
                        @csrf
                        <div class="flex items-center gap-2">
                            <select name="rating" class="border rounded px-2 py-1" required>
                                <option value="">Rating</option>
                                <option value="5">★★★★★</option>
                                <option value="4">★★★★</option>
                                <option value="3">★★★</option>
                                <option value="2">★★</option>
                                <option value="1">★</option>
                            </select>

                            <input type="text" name="review" placeholder="Tulis ulasan (opsional)"
                                   class="border rounded px-3 py-1 w-full">

                            <button class="bg-green-600 text-white px-4 py-1 rounded">
                                Kirim
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <p class="text-gray-500">Belum ada histori booking.</p>
    @endforelse
</div>
@endsection
