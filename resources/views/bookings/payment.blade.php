@extends('layouts.app')

@section('content')
<h1 class="text-xl font-bold mb-4">Pembayaran Booking</h1>

<script
  src="https://app.sandbox.midtrans.com/snap/snap.js"
  data-client-key="{{ config('midtrans.client_key') }}">
</script>

<button id="pay-button"
        class="bg-green-600 text-white px-4 py-2 rounded">
    Bayar Sekarang
</button>

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
