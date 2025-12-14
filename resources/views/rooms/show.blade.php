@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">{{ $room->name }}</h1>

<div class="bg-white p-6 rounded-xl shadow space-y-4">
    <p><strong>Hotel:</strong> {{ $room->hotel->name }}</p>
    <p><strong>Harga:</strong> Rp {{ number_format($room->price) }}</p>
    <p><strong>Kapasitas:</strong> {{ $room->capacity }} orang</p>
    <p><strong>Stok:</strong> {{ $room->stock }}</p>
    <p><strong>Deskripsi:</strong> {{ $room->description }}</p>

    {{-- Thumbnail --}}
    @if ($room->thumbnail)
        <div>
            <h3 class="font-semibold mb-2">Thumbnail</h3>
            <img src="{{ asset('storage/' . $room->thumbnail) }}"
                 class="w-64 rounded-lg">
        </div>
    @endif

    {{-- Gallery --}}
    @if ($room->images)
        <div>
            <h3 class="font-semibold mb-2">Galeri</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($room->images as $img)
                    <img src="{{ asset('storage/' . $img) }}"
                         class="w-full h-40 object-cover rounded-lg">
                @endforeach
            </div>
        </div>
    @endif

    <a href="{{ route('rooms.index') }}"
       class="inline-block mt-4 px-4 py-2 rounded-lg border">
        Kembali
    </a>
</div>
@endsection
