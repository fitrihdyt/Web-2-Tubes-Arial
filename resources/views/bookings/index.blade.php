@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                My Bookings
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                your booking history
            </p>
        </div>
    </div>

    @if ($bookings->isEmpty())
        <div class="bg-white rounded-3xl shadow p-10 text-center text-gray-500">
            You don’t have any bookings yet.
        </div>
    @else

        <div class="grid gap-6">
            @foreach ($bookings as $booking)
                <div class="bg-white rounded-3xl shadow p-6">

                    {{-- TOP --}}
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="font-semibold text-gray-800">
                                {{ $booking->room->hotel->name }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ $booking->room->name }}
                            </p>
                        </div>

                        {{-- STATUS --}}
                        @if ($booking->status === 'paid')
                            <span class="px-4 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                Paid
                            </span>
                        @elseif ($booking->status === 'pending')
                            <span class="px-4 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                Pending
                            </span>
                        @else
                            <span class="px-4 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                Cancelled
                            </span>
                        @endif
                    </div>

                    {{-- INFO --}}
                    <div class="grid md:grid-cols-4 gap-4 text-sm">

                        <div>
                            <p class="text-gray-400">Check-in</p>
                            <p class="font-semibold text-gray-700">
                                {{ $booking->check_in }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-400">Check-out</p>
                            <p class="font-semibold text-gray-700">
                                {{ $booking->check_out }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-400">Total</p>
                            <p class="font-semibold text-[#ff5a1f]">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="flex items-end gap-3">
                            <a href="{{ route('bookings.show', $booking->id) }}"
                               class="text-sm text-gray-600 hover:underline">
                                Detail
                            </a>

                            @if ($booking->status === 'pending')
                                <form action="{{ route('bookings.pay', $booking->id) }}" method="POST">
                                    @csrf
                                    <button
                                        class="px-4 py-2 rounded-xl bg-[#134662] text-white text-xs font-semibold hover:bg-[#0f3a4e] transition">
                                        Pay
                                    </button>
                                </form>

                                {{-- CANCEL BUTTON --}}
                                <form action="{{ route('bookings.destroy', $booking->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="px-4 py-2 rounded-xl bg-red-100 text-red-600 text-xs font-semibold hover:bg-red-200 transition">
                                        Cancel
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="mt-6 flex items-center justify-between border-t pt-4">
                        <span class="text-sm text-gray-500">
                            Booking created:
                            {{ $booking->created_at->format('d M Y') }}
                        </span>
                    </div>

                </div>
            @endforeach
        </div>

    @endif

</div>
@endsection
