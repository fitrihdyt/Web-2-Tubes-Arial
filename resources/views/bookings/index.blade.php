@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6">

    <h1 class="text-2xl font-semibold mb-6">My Bookings</h1>

    @if ($bookings->isEmpty())
        <div class="bg-white p-6 rounded-xl border text-neutral-600">
            You don’t have any bookings yet.
        </div>
    @else
        <div class="overflow-x-auto bg-white rounded-xl border">
            <table class="w-full text-sm">
                <thead class="bg-neutral-100 text-neutral-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Hotel</th>
                        <th class="px-4 py-3 text-left">Room</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Total</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach ($bookings as $booking)
                        <tr class="hover:bg-neutral-50 transition">
                            <td class="px-4 py-3 font-medium">
                                {{ $booking->room->hotel->name }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $booking->room->name }}
                            </td>

                            <td class="px-4 py-3 text-neutral-600">
                                {{ $booking->check_in }} → {{ $booking->check_out }}
                            </td>

                            <td class="px-4 py-3">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3">
                                @if ($booking->status === 'paid')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                        Paid
                                    </span>
                                @elseif ($booking->status === 'pending')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                        Pending
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                        Cancelled
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('bookings.show', $booking->id) }}"
                                   class="inline-block text-sm text-blue-600 hover:underline">
                                    View Detail
                                </a>

                                @if ($booking->status === 'pending')
                                    <form action="{{ route('bookings.pay', $booking->id) }}"
                                          method="POST"
                                          class="inline-block ml-2">
                                        @csrf
                                        <button
                                            class="text-sm text-white bg-neutral-900 px-3 py-1 rounded-lg hover:bg-neutral-800 transition">
                                            Pay
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
@endsection
