@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <!-- PAGE HEADER -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-900">My Bookings</h1>
        <p class="text-sm text-neutral-500 mt-1">
            View and manage your hotel reservations
        </p>
    </div>

    @if ($bookings->isEmpty())
        <div class="bg-white border rounded-2xl p-10 text-center">
            <svg class="w-12 h-12 mx-auto text-neutral-400 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-neutral-600">
                You don’t have any bookings yet.
            </p>
        </div>
    @else
        <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-neutral-50 text-neutral-600">
                        <tr>
                            <th class="px-6 py-4 text-left font-medium">Hotel</th>
                            <th class="px-6 py-4 text-left font-medium">Room</th>
                            <th class="px-6 py-4 text-left font-medium">Stay Date</th>
                            <th class="px-6 py-4 text-left font-medium">Total</th>
                            <th class="px-6 py-4 text-left font-medium">Status</th>
                            <th class="px-6 py-4 text-center font-medium">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @foreach ($bookings as $booking)
                            <tr class="hover:bg-neutral-50 transition">
                                <!-- HOTEL -->
                                <td class="px-6 py-4 font-medium text-neutral-900">
                                    {{ $booking->room->hotel->name }}
                                </td>

                                <!-- ROOM -->
                                <td class="px-6 py-4 text-neutral-700">
                                    {{ $booking->room->name }}
                                </td>

                                <!-- DATE -->
                                <td class="px-6 py-4 text-neutral-600">
                                    {{ $booking->check_in }} <span class="mx-1">–</span> {{ $booking->check_out }}
                                </td>

                                <!-- TOTAL -->
                                <td class="px-6 py-4 font-medium">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </td>

                                <!-- STATUS -->
                                <td class="px-6 py-4">
                                    @if ($booking->status === 'paid')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Paid
                                        </span>
                                    @elseif ($booking->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M12 8v4l3 3"/>
                                            </svg>
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Cancelled
                                        </span>
                                    @endif
                                </td>

                                <!-- ACTION -->
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('bookings.show', $booking->id) }}"
                                           class="inline-flex items-center gap-1 text-sm text-neutral-700 hover:text-neutral-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>

                                        @if ($booking->status === 'pending')
                                            <form action="{{ route('bookings.pay', $booking->id) }}" method="POST">
                                                @csrf
                                                <button
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-neutral-900 text-white text-xs hover:bg-neutral-800 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M17 9V7a5 5 0 00-10 0v2M5 9h14l-1 12H6L5 9z"/>
                                                    </svg>
                                                    Pay
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
