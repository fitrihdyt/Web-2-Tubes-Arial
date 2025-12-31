@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Room</h1>

        @auth
            @if(auth()->user()->role === 'hotel_admin')
                <a href="{{ route('rooms.create') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700
                          text-white px-4 py-2 rounded-lg shadow">
                    <span class="text-lg">＋</span>
                    Tambah Room
                </a>
            @endif
        @endauth
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="p-4">Foto</th>
                    <th class="p-4">Hotel</th>
                    <th class="p-4">Room</th>
                    <th class="p-4">Harga</th>
                    <th class="p-4">Kapasitas</th>
                    <th class="p-4">Stok</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($rooms as $room)
                    <tr class="hover:bg-gray-50 transition">

                        {{-- FOTO --}}
                        <td class="p-4">
                            @if ($room->thumbnail)
                                <img src="{{ asset('storage/' . $room->thumbnail) }}"
                                     class="w-14 h-14 object-cover rounded-lg border">
                            @else
                                <div class="w-14 h-14 flex items-center justify-center
                                            bg-gray-100 rounded-lg text-gray-400">
                                    N/A
                                </div>
                            @endif
                        </td>

                        {{-- HOTEL --}}
                        <td class="p-4 font-medium text-gray-700">
                            {{ $room->hotel->name }}
                        </td>

                        {{-- ROOM --}}
                        <td class="p-4">
                            <div class="font-semibold text-gray-800">
                                {{ $room->name }}
                            </div>
                        </td>

                        {{-- HARGA --}}
                        <td class="p-4 text-gray-700">
                            Rp {{ number_format($room->price) }}
                            <div class="text-xs text-gray-400">/ malam</div>
                        </td>

                        {{-- KAPASITAS --}}
                        <td class="p-4 text-gray-700">
                            {{ $room->capacity }} orang
                        </td>

                        {{-- STOK --}}
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-1
                                         rounded-full text-xs font-semibold
                                         {{ $room->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $room->stock > 0 ? $room->stock . ' tersedia' : 'Habis' }}
                            </span>
                        </td>

                        {{-- AKSI --}}
                        <td class="p-4">
                            <div class="flex justify-center gap-3">

                                <a href="{{ route('rooms.show', $room) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Detail
                                </a>

                                @if(auth()->check() && auth()->user()->role === 'hotel_admin')
                                    <a href="{{ route('rooms.edit', $room) }}"
                                       class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                                        Edit
                                    </a>

                                    <form action="{{ route('rooms.destroy', $room) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus room ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7"
                            class="p-8 text-center text-gray-400">
                            Belum ada room yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
