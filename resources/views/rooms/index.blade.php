@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 space-y-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">
            Daftar Room
        </h1>

        @auth
            @if(auth()->user()->role === 'hotel_admin')
                <a href="{{ route('rooms.create') }}"
                   class="bg-[#134662] hover:bg-[#0f3a4e]
                          text-white px-5 py-2.5 rounded-xl shadow">
                    + Tambah Room
                </a>
            @endif
        @endauth
    </div>

    {{-- EMPTY --}}
    @if($rooms->isEmpty())
        <div class="bg-white rounded-2xl p-16 text-center text-gray-400 shadow">
            Belum ada room yang ditambahkan
        </div>
    @else

    {{-- LIST --}}
    <div class="space-y-6">
        @foreach($rooms as $room)

        <div class="bg-white rounded-2xl shadow hover:shadow-lg transition
                    overflow-hidden flex">

            {{-- IMAGE --}}
            <a href="{{ route('rooms.show', $room) }}"
               class="w-[240px] h-[180px] shrink-0 relative group overflow-hidden">
                @if($room->thumbnail)
                    <img src="{{ asset('storage/'.$room->thumbnail) }}"
                         class="w-full h-full object-cover
                                group-hover:scale-105 transition duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center
                                bg-gray-100 text-gray-400">
                        No Image
                    </div>
                @endif
            </a>

            {{-- INFO --}}
            <div class="flex-1 px-6 py-4 flex flex-col justify-between">

                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ $room->name }}
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        {{ $room->hotel->name }}
                    </p>

                    <div class="flex items-center gap-4 mt-3 text-sm text-gray-600">
                        <div class="flex items-center gap-1">
                            {{-- USER ICON --}}
                            <svg class="w-4 h-4 text-gray-400"
                                 fill="none" stroke="currentColor" stroke-width="1.8"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8z"/>
                            </svg>
                            {{ $room->capacity }} orang
                        </div>

                        <div>
                            Stok:
                            <span class="font-semibold
                                {{ $room->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $room->stock > 0 ? $room->stock : 'Habis' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- ADMIN ACTION --}}
                @auth
                    @if(auth()->user()->role === 'hotel_admin')
                        <div class="flex gap-4 text-xs mt-4">
                            <a href="{{ route('rooms.edit', $room) }}"
                               class="text-yellow-600 hover:underline">
                                Edit
                            </a>

                            <form action="{{ route('rooms.destroy', $room) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus room ini?')">
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
            <div class="w-[220px] bg-gray-50 px-6 py-4
                        flex flex-col justify-center items-end border-l">

                <p class="text-xl font-bold text-[#ff5a1f]">
                    Rp {{ number_format($room->price, 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-400 mb-4">
                    / malam
                </p>

                <a href="{{ route('rooms.show', $room) }}"
                   class="bg-[#ff5a1f] hover:bg-[#e64a19]
                          text-white px-5 py-2 rounded-lg
                          text-sm font-semibold">
                    Lihat Detail
                </a>
            </div>

        </div>

        @endforeach
    </div>
    @endif

</div>
@endsection
