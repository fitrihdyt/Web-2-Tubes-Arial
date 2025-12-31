@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">
            Daftar Hotel
        </h1>

        @auth
            @if(auth()->user()->role === 'hotel_admin')
                <a href="{{ route('hotels.create') }}"
                   class="bg-[#134662] hover:bg-[#0f3a4e] text-white px-5 py-2.5 rounded-xl shadow">
                    + Tambah Hotel
                </a>
            @endif
        @endauth
    </div>

    {{-- EMPTY --}}
    @if($hotels->isEmpty())
        <div class="bg-white rounded-2xl p-16 text-center text-gray-400 shadow">
            Belum ada data hotel
        </div>
    @else

    {{-- LIST --}}
    <div class="space-y-6">
    @foreach($hotels as $hotel)

        <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden flex">

            {{-- IMAGE --}}
            <a href="{{ route('hotels.show', $hotel) }}"
               class="w-[260px] h-[200px] shrink-0 relative group overflow-hidden">
                <img
                    src="{{ $hotel->thumbnail ? asset('storage/'.$hotel->thumbnail) : 'https://via.placeholder.com/400x300' }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                >
            </a>

            {{-- INFO --}}
            <div class="flex-1 px-6 py-4 flex flex-col justify-between">

                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ $hotel->name }}
                    </h2>

                    {{-- STAR --}}
                    <div class="flex items-center gap-1 text-yellow-500 text-sm mt-1">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $hotel->star)
                                ★
                            @else
                                <span class="text-gray-300">★</span>
                            @endif
                        @endfor
                        <span class="text-gray-400 ml-2 text-xs">
                            {{ $hotel->star }}/5
                        </span>
                    </div>

                    {{-- LOCATION (SVG) --}}
                    <div class="flex items-center gap-1.5 text-sm text-gray-500 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-4 h-4 text-gray-400"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5
                                     9 6.343 9 8s1.343 3 3 3z"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M19.5 8c0 7-7.5 11-7.5 11S4.5 15 4.5 8
                                     a7.5 7.5 0 1115 0z"/>
                        </svg>
                        <span>{{ $hotel->city }}</span>
                    </div>

                    {{-- DESCRIPTION --}}
                    <p class="text-sm text-gray-600 mt-2">
                        {{ \Illuminate\Support\Str::limit($hotel->description, 80) }}
                    </p>

                    {{-- FASILITAS --}}
                    @if($hotel->facilities->count())
                        <div class="flex flex-wrap gap-2 mt-3">
                            @foreach($hotel->facilities->take(3) as $facility)
                                <span class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full">
                                    {{ $facility->pivot->custom_name ?? $facility->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ADMIN --}}
                @auth
                    @if(in_array(auth()->user()->role, ['hotel_admin','super_admin']))
                        <div class="flex gap-4 text-xs mt-3">
                            <a href="{{ route('hotels.edit', $hotel) }}"
                               class="text-yellow-600 hover:underline">
                                Edit
                            </a>

                            <form action="{{ route('hotels.destroy', $hotel) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin hapus hotel ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>

            {{-- PRICE --}}
            <div class="w-[220px] bg-gray-50 px-6 py-4 flex flex-col justify-center items-end border-l">

                <div class="text-right">
                    <p class="text-xl font-bold text-[#ff5a1f]">
                        Rp {{ number_format($hotel->rooms_min_price ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400">
                        / malam
                    </p>
                </div>

                <a href="{{ route('hotels.show', $hotel) }}"
                   class="mt-4 bg-[#ff5a1f] hover:bg-[#e64a19]
                          text-white px-5 py-2 rounded-lg text-sm font-semibold">
                    Pilih Kamar
                </a>
            </div>

        </div>

    @endforeach
    </div>
    @endif

</div>
@endsection
