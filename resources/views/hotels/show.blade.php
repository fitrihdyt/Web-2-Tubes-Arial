@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <div class="flex gap-6">
        <div class="w-64 h-40 bg-gray-100 rounded-lg overflow-hidden">
            @if($hotel->thumbnail)
                <img src="{{ asset('storage/' . $hotel->thumbnail) }}"
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    No Image
                </div>
            @endif
        </div>

        <div class="flex-1">
            <h1 class="text-3xl font-bold">{{ $hotel->name }}</h1>

            <div class="flex items-center gap-1 text-yellow-500 mt-1">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $hotel->star)
                        ★
                    @else
                        <span class="text-gray-300">★</span>
                    @endif
                @endfor
                <span class="text-gray-400 ml-1">({{ $hotel->star }})</span>
            </div>

            <p class="text-gray-500 mt-2">{{ $hotel->city }}</p>

            <p class="text-gray-700 mt-3">
                {{ $hotel->description ?? 'Tidak ada deskripsi.' }}
            </p>
        </div>
    </div>
</div>

<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Daftar Room</h2>

    @if(auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('rooms.create', ['hotel_id' => $hotel->id]) }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            + Tambah Room
        </a>
    @endif
</div>

@if($hotel->rooms->isEmpty())
    <div class="bg-white p-6 rounded-lg shadow text-center text-gray-500">
        Belum ada room untuk hotel ini
    </div>
@else
<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3">Foto</th>
                <th class="p-3">Nama</th>
                <th class="p-3">Harga</th>
                <th class="p-3">Kapasitas</th>
                <th class="p-3">Stok</th>
                <th class="p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hotel->rooms as $room)
            <tr class="border-t">
                <td class="p-3">
                    @if ($room->thumbnail)
                        <img src="{{ asset('storage/' . $room->thumbnail) }}"
                             class="w-16 h-16 object-cover rounded-lg">
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="p-3 font-medium">{{ $room->name }}</td>
                <td class="p-3">Rp {{ number_format($room->price) }}</td>
                <td class="p-3">{{ $room->capacity }} org</td>
                <td class="p-3">{{ $room->stock }}</td>
                <td class="p-3 flex gap-3">
                    <a href="{{ route('rooms.show', $room) }}" class="text-blue-600">
                        Detail
                    </a>

                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('rooms.edit', $room) }}" class="text-yellow-600">
                            Edit
                        </a>

                        <form action="{{ route('rooms.destroy', $room) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus room ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600">Hapus</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
