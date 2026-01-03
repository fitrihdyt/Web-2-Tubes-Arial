@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4">

    <h1 class="text-3xl font-bold mb-8 text-gray-800">
        Booking Kamar
    </h1>

    <div class="grid md:grid-cols-3 gap-6">

        {{-- ROOM INFO --}}
        <div class="md:col-span-1 bg-white rounded-3xl shadow p-6 space-y-4">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ $room->name }}
            </h2>

            <p class="text-sm text-gray-500">
                {{ $room->hotel->name }}
            </p>

            <div class="border-t pt-4 space-y-2">
                <p class="text-sm text-gray-500">Harga / malam</p>
                <p class="text-2xl font-bold text-[#ff5a1f]">
                    Rp {{ number_format($room->price, 0, ',', '.') }}
                </p>

                <p class="text-sm text-gray-500">
                    Stok tersedia: {{ $room->stock }} kamar
                </p>
            </div>
        </div>

        {{-- FORM --}}
        <div class="md:col-span-2 bg-white rounded-3xl shadow p-8">

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="room_id" value="{{ $room->id }}">

                {{-- DATE --}}
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Check-in
                        </label>
                        <input type="date"
                               name="check_in"
                               value="{{ old('check_in') }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-300
                                      focus:ring-2 focus:ring-[#ff5a1f] focus:outline-none"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Check-out
                        </label>
                        <input type="date"
                               name="check_out"
                               value="{{ old('check_out') }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-300
                                      focus:ring-2 focus:ring-[#ff5a1f] focus:outline-none"
                               required>
                    </div>
                </div>

                {{-- QTY --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Jumlah Kamar
                    </label>
                    <input type="number"
                           name="qty"
                           min="1"
                           max="{{ $room->stock }}"
                           value="{{ old('qty', 1) }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-300
                                  focus:ring-2 focus:ring-[#ff5a1f] focus:outline-none"
                           required>
                </div>

                {{-- ACTION --}}
                <div class="pt-4">
                    <button type="submit"
                            class="w-full bg-[#ff5a1f] hover:bg-[#e64a19]
                                   text-white py-4 rounded-xl font-semibold
                                   transition shadow-lg hover:shadow-xl">
                        Konfirmasi Booking
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
