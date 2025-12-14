@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Daftar Room</h1>

<a href="{{ route('rooms.create') }}"
   class="bg-blue-600 text-white px-4 py-2 rounded-lg mb-4 inline-block">
    + Tambah Room
</a>

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
            @foreach ($rooms as $room)
            <tr class="border-t">
                <td class="p-3">
                    @if ($room->thumbnail)
                        <img src="{{ asset('storage/' . $room->thumbnail) }}"
                             class="w-16 h-16 object-cover rounded-lg">
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="p-3">{{ $room->hotel->name }}</td>
                <td class="p-3">{{ $room->name }}</td>
                <td class="p-3">Rp {{ number_format($room->price) }}</td>
                <td class="p-3">{{ $room->capacity }} org</td>
                <td class="p-3">{{ $room->stock }}</td>
                <td class="p-3 flex gap-2">
                    <a href="{{ route('rooms.show', $room) }}" class="text-blue-600">Detail</a>
                    <a href="{{ route('rooms.edit', $room) }}" class="text-yellow-600">Edit</a>
                    <form action="{{ route('rooms.destroy', $room) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus room ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
