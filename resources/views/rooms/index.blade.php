@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Daftar Room</h1>

{{-- BUTTON TAMBAH ROOM --}}
@auth
    @if(auth()->user()->role === 'hotel_admin')
        <a href="{{ route('rooms.create') }}"
           class="inline-block mb-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            + Tambah Room
        </a>
    @endif
@endauth

<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3">Foto</th>
                <th class="p-3">Hotel</th>
                <th class="p-3">Nama</th>
                <th class="p-3">Harga</th>
                <th class="p-3">Kapasitas</th>
                <th class="p-3">Stok</th>
                <th class="p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rooms as $room)
                <tr class="border-t">
                    {{-- FOTO --}}
                    <td class="p-3">
                        @if ($room->thumbnail)
                            <img src="{{ asset('storage/' . $room->thumbnail) }}"
                                 class="w-16 h-16 object-cover rounded-lg">
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>

                    {{-- HOTEL --}}
                    <td class="p-3">
                        {{ $room->hotel->name }}
                    </td>

                    {{-- NAMA --}}
                    <td class="p-3">
                        {{ $room->name }}
                    </td>

                    {{-- HARGA --}}
                    <td class="p-3">
                        Rp {{ number_format($room->price) }}
                    </td>

                    {{-- KAPASITAS --}}
                    <td class="p-3">
                        {{ $room->capacity }} org
                    </td>

                    {{-- STOCK FISIK --}}
                    <td class="p-3">
                        {{ $room->stock }}
                    </td>

                    {{-- AKSI --}}
                    <td class="p-3">
                        <div class="flex gap-3 items-center">
                            <a href="{{ route('rooms.show', $room) }}"
                               class="text-blue-600 hover:underline">
                                Detail
                            </a>

                            @if(auth()->check() && auth()->user()->role === 'hotel_admin')
                                <a href="{{ route('rooms.edit', $room) }}"
                                   class="text-yellow-600 hover:underline">
                                    Edit
                                </a>

                                <form action="{{ route('rooms.destroy', $room) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus room ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:underline">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-6 text-center text-gray-500">
                        Belum ada room.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
