@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4">
    <div class="mt-5 mb-10">
        <form id="hotelSearchForm"
            method="GET"
            action="{{ route('hotels.index') }}"
            class="bg-white/90 backdrop-blur
                rounded-2xl shadow-md
                px-5 py-4
                grid grid-cols-1 md:grid-cols-7 gap-3
                transition">

            {{-- SEARCH --}}
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Kota atau nama hotel"
                class="md:col-span-4
                    px-4 py-2.5
                    rounded-xl border border-gray-200
                    text-[#134662] placeholder:text-gray-500
                    focus:ring-2 focus:ring-[#134662]
                    focus:border-[#134662]
                    transition"
            >

            {{-- PRICE --}}
            <select
                name="price"
                id="priceFilter"
                class="md:col-span-2
                    px-4 py-2.5
                    rounded-xl border border-gray-200
                    text-[#134662]
                    focus:ring-2 focus:ring-[#134662]
                    transition">

                <option value="">Semua Harga</option>

                        <option value="0-500"
                            {{ request('price') == '0-500' ? 'selected' : '' }}>
                            < Rp 500.000
                        </option>

                        <option value="500-1000"
                            {{ request('price') == '500-1000' ? 'selected' : '' }}>
                            Rp 500.000 - Rp 1.000.000
                        </option>

                        <option value="1000+"
                            {{ request('price') == '1000+' ? 'selected' : '' }}>
                            > Rp 1.000.000
                        </option>
            </select>

            {{-- BUTTON --}}
            <button
                type="submit"
                class="md:col-span-1
                    flex items-center justify-center
                    bg-[#134662] hover:bg-[#0f3a4e]
                    text-white rounded-xl
                    h-[42px]
                    transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M21 21l-4.35-4.35m1.85-5.65a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>

        </form>
    </div>


    </form>
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

        <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden flex group">

            {{-- CARD LINK (IMAGE + INFO) --}}
            <a href="{{ route('hotels.show', $hotel) }}"
            class="flex flex-1 no-underline">

                {{-- IMAGE --}}
                <div class="w-[260px] h-[200px] shrink-0 relative overflow-hidden">
                    <img
                        src="{{ $hotel->thumbnail
                            ? asset('storage/'.$hotel->thumbnail)
                            : 'https://via.placeholder.com/400x300' }}"
                        class="w-full h-full object-cover
                            group-hover:scale-105 transition duration-500"
                    >
                </div>

                {{-- INFO --}}
                <div class="flex-1 px-6 py-4 flex flex-col justify-between">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            {{ $hotel->name }}
                        </h2>

                        {{-- STAR --}}
                        <div class="flex items-center gap-1 text-yellow-500 text-sm mt-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $hotel->star ? '' : 'text-gray-300' }}">★</span>
                            @endfor
                            <span class="text-gray-400 ml-2 text-xs">
                                {{ $hotel->star }}/5
                            </span>
                        </div>

                        {{-- LOCATION --}}
                        <div class="flex items-center gap-1.5 text-sm text-gray-500 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5
                                        9 6.343 9 8s1.343 3 3 3z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.5 8c0 7-7.5 11-7.5 11S4.5 15 4.5 8
                                        a7.5 7.5 0 1115 0z"/>
                            </svg>
                            <span>{{ $hotel->city }}</span>
                        </div>

                        <p class="text-sm text-gray-600 mt-2">
                            {{ \Illuminate\Support\Str::limit($hotel->description, 80) }}
                        </p>

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

                </div>
            </a>

            {{-- PRICE & ACTION --}}
            <div class="w-[220px] bg-gray-50 px-6 py-4 flex flex-col justify-center items-end border-l">

                <div class="text-right">
                    <p class="text-xl font-bold text-[#ff5a1f]">
                        Rp {{ number_format($hotel->rooms_min_price ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400">/ malam</p>
                </div>

                <a href="{{ route('hotels.show', $hotel) }}"
                class="mt-4 bg-[#ff5a1f] hover:bg-[#e64a19]
                        text-white px-5 py-2 rounded-lg text-sm font-semibold">
                    Pilih Kamar
                </a>

                {{-- ADMIN --}}
                @auth
                    <div class="flex gap-4 text-xs mt-4">
                        @if(auth()->user()->role === 'hotel_admin')
                            <a href="{{ route('hotels.edit', $hotel) }}"
                            class="text-slate-600 hover:underline">
                                Edit
                            </a>
                        @endif

                        @if(auth()->user()->role === 'super_admin')
                            <form action="{{ route('hotels.destroy', $hotel) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin hapus hotel ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-slate-600 hover:underline">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                @endauth

            </div>
        </div>

@endforeach

    </div>
    @endif

</div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', () => {
    const price = document.getElementById('priceFilter')
    const form = document.getElementById('hotelSearchForm')

    if (price) {
        price.addEventListener('change', () => {
            form.submit()
        })
    }
})
</script>
