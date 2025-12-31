@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">

    <h1 class="text-2xl font-bold mb-6">Pembayaran Booking</h1>

    <div class="bg-white rounded-2xl shadow p-6 space-y-4">

        <div class="border-b pb-4">
            <p class="text-sm text-gray-500">Hotel</p>
            <p class="font-semibold text-lg">
                {{ $booking->room->hotel->name }}
            </p>

            <p class="text-sm text-gray-500 mt-2">Kamar</p>
            <p class="font-medium">
                {{ $booking->room->name }}
            </p>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Check-in</p>
                <p class="font-medium">{{ $booking->check_in }}</p>
            </div>
            <div>
                <p class="text-gray-500">Check-out</p>
                <p class="font-medium">{{ $booking->check_out }}</p>
            </div>
            <div>
                <p class="text-gray-500">Jumlah Kamar</p>
                <p class="font-medium">{{ $booking->qty }}</p>
            </div>
            <div>
                <p class="text-gray-500">Total Harga</p>
                <p class="font-bold text-[#ff5a1f]">
                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="pt-6">
            <button id="pay-button"
                class="w-full bg-green-600 hover:bg-green-700
                       text-white py-3 rounded-xl font-semibold">
                Bayar Sekarang
            </button>
        </div>
    </div>
</div>

<script
  src="https://app.sandbox.midtrans.com/snap/snap.js"
  data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
document.getElementById('pay-button').addEventListener('click', function () {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function () {
            window.location.href = "{{ route('bookings.show', $booking->id) }}";
        },
        onPending: function () {
            window.location.href = "{{ route('bookings.show', $booking->id) }}";
        },
        onError: function () {
            alert('Pembayaran gagal');
        }
    });
});
</script>
@endsection
