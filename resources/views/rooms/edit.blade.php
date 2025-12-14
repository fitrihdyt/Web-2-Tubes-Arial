@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Room</h1>

<form action="{{ route('rooms.update', $room) }}"
      method="POST"
      enctype="multipart/form-data"
      class="bg-white p-6 rounded-xl shadow space-y-4">
    @csrf
    @method('PUT')

    <select name="hotel_id" class="w-full border rounded p-2" required>
        @foreach ($hotels as $hotel)
            <option value="{{ $hotel->id }}"
                {{ $room->hotel_id == $hotel->id ? 'selected' : '' }}>
                {{ $hotel->name }}
            </option>
        @endforeach
    </select>

    <input type="text" name="name" value="{{ $room->name }}"
           class="w-full border rounded p-2" required>

    <input type="number" name="price" value="{{ $room->price }}"
           class="w-full border rounded p-2" required>

    <div class="grid grid-cols-2 gap-4">
        <input type="number" name="capacity" value="{{ $room->capacity }}"
               class="border rounded p-2" required>
        <input type="number" name="stock" value="{{ $room->stock }}"
               class="border rounded p-2" required>
    </div>

    <textarea name="description"
              class="w-full border rounded p-2">{{ $room->description }}</textarea>

    {{-- Thumbnail lama --}}
    @if ($room->thumbnail)
        <img src="{{ asset('storage/' . $room->thumbnail) }}"
             class="w-32 rounded-lg mb-2">
    @endif

    <div>
        <label class="block font-medium mb-1">Ganti Thumbnail</label>
        <input type="file" name="thumbnail"
               class="w-full border rounded p-2">
    </div>

    {{-- Gallery lama --}}
    @if ($room->images)
        <div class="grid grid-cols-4 gap-3 mt-2">
            @foreach ($room->images as $img)
                <img src="{{ asset('storage/' . $img) }}"
                     class="h-24 object-cover rounded-lg">
            @endforeach
        </div>
    @endif

    <div class="mt-3">
        <label class="block font-medium mb-1">Tambah Gambar Baru</label>
        <input type="file" name="images[]" multiple
               class="w-full border rounded p-2">
    </div>

    <div class="flex gap-3">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">Update</button>
        <a href="{{ route('rooms.index') }}" class="px-4 py-2 rounded-lg border">Batal</a>
    </div>
</form>
@endsection
