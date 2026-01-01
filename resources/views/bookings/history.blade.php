@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-semibold text-slate-800 mb-6">
        Histori Booking
    </h1>

    @forelse($bookings as $booking)
        <div class="bg-white rounded-xl border border-slate-200 p-5 mb-4">
            {{-- Header --}}
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-lg font-medium text-slate-800">
                        {{ $booking->hotel->name }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        {{ $booking->check_in }} &ndash; {{ $booking->check_out }}
                    </p>
                </div>

                <span class="inline-flex items-center gap-1 text-sm font-medium text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    Selesai
                </span>
            </div>

            {{-- Rating / Review --}}
            <div class="mt-4">
                @if($booking->rating)
                    {{-- Star display --}}
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5 {{ $i <= $booking->rating ? 'text-amber-400' : 'text-slate-300' }}"
                                 fill="currentColor"
                                 viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                        @endfor
                    </div>

                    @if($booking->review)
                        <p class="text-sm text-slate-600 mt-2">
                            {{ $booking->review }}
                        </p>
                    @endif
                @else
                    {{-- Form rating --}}
                    <form action="{{ route('bookings.rating', $booking->id) }}" method="POST" class="mt-2">
                        @csrf
                        <div class="flex flex-col md:flex-row gap-3">
                            <select name="rating"
                                    class="border border-slate-300 rounded-lg px-3 py-2 text-sm text-slate-700 focus:ring focus:ring-slate-200"
                                    required>
                                <option value="">Pilih rating</option>
                                <option value="5">5 - Sangat baik</option>
                                <option value="4">4 - Baik</option>
                                <option value="3">3 - Cukup</option>
                                <option value="2">2 - Kurang</option>
                                <option value="1">1 - Buruk</option>
                            </select>

                            <input type="text"
                                   name="review"
                                   placeholder="Tulis ulasan (opsional)"
                                   class="border border-slate-300 rounded-lg px-3 py-2 text-sm w-full focus:ring focus:ring-slate-200">

                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2
                                           bg-slate-700 hover:bg-slate-800
                                           text-white text-sm font-medium
                                           px-5 py-2 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M5 13l4 4L19 7"/>
                                </svg>
                                Kirim
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center text-slate-500 py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-slate-300"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 17v-2a4 4 0 014-4h4"/>
            </svg>
            Belum ada histori booking.
        </div>
    @endforelse
</div>
@endsection
