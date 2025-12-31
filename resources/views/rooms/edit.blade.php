@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 space-y-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">
            Edit Room
        </h1>
        <a href="{{ route('rooms.index') }}"
           class="text-sm text-gray-500 hover:underline">
            Kembali
        </a>
    </div>

    <form action="{{ route('rooms.update', $room) }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-white rounded-2xl shadow p-6 space-y-8">
        @csrf
        @method('PUT')

        {{-- ================= BASIC INFO ================= --}}
        <div class="grid md:grid-cols-2 gap-6">

            {{-- HOTEL --}}
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">
                    Hotel
                </label>
                <select name="hotel_id"
                        class="mt-1 w-full border rounded-xl px-4 py-2"
                        required>
                    @foreach ($hotels as $hotel)
                        <option value="{{ $hotel->id }}"
                            {{ $room->hotel_id == $hotel->id ? 'selected' : '' }}>
                            {{ $hotel->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ROOM NAME --}}
            <div>
                <label class="text-sm font-medium text-gray-600">
                    Nama Room
                </label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $room->name) }}"
                       class="mt-1 w-full border rounded-xl px-4 py-2"
                       required>
            </div>

            {{-- PRICE --}}
            <div>
                <label class="text-sm font-medium text-gray-600">
                    Harga / malam
                </label>
                <input type="number"
                       name="price"
                       value="{{ old('price', $room->price) }}"
                       class="mt-1 w-full border rounded-xl px-4 py-2"
                       required>
            </div>

            {{-- CAPACITY --}}
            <div>
                <label class="text-sm font-medium text-gray-600">
                    Kapasitas (orang)
                </label>
                <input type="number"
                       name="capacity"
                       value="{{ old('capacity', $room->capacity) }}"
                       class="mt-1 w-full border rounded-xl px-4 py-2"
                       required>
            </div>

            {{-- STOCK --}}
            <div>
                <label class="text-sm font-medium text-gray-600">
                    Stok Kamar
                </label>
                <input type="number"
                       name="stock"
                       value="{{ old('stock', $room->stock) }}"
                       class="mt-1 w-full border rounded-xl px-4 py-2"
                       required>
            </div>

            {{-- DESCRIPTION --}}
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">
                    Deskripsi Room
                </label>
                <textarea name="description"
                          rows="4"
                          class="mt-1 w-full border rounded-xl px-4 py-2">{{ old('description', $room->description) }}</textarea>
            </div>
        </div>

        {{-- ================= THUMBNAIL ================= --}}
        <div>
            <label class="text-sm font-medium text-gray-600 block mb-2">
                Thumbnail Room
            </label>

            @if ($room->thumbnail)
                <img src="{{ asset('storage/' . $room->thumbnail) }}"
                     class="w-40 h-28 object-cover rounded-xl mb-3">
            @endif

            <input type="file"
                   name="thumbnail"
                   class="w-full border rounded-xl px-4 py-2 text-sm">
        </div>

        {{-- ================= GALLERY ================= --}}
        <div>
            <label class="text-sm font-medium text-gray-600 block mb-2">
                Galeri Room
            </label>

            @if ($room->images)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                    @foreach ($room->images as $img)
                        <img src="{{ asset('storage/' . $img) }}"
                             class="h-24 w-full object-cover rounded-xl">
                    @endforeach
                </div>
            @endif

            <input type="file"
                   name="images[]"
                   multiple
                   class="w-full border rounded-xl px-4 py-2 text-sm">
        </div>

        {{-- ================= ACTION ================= --}}
        <div class="flex gap-4 pt-4">
            <button class="bg-[#134662] hover:bg-[#0f3a4e]
                           text-white px-6 py-3 rounded-xl font-semibold">
                Update Room
            </button>

            <a href="{{ route('rooms.index') }}"
               class="px-6 py-3 rounded-xl border text-gray-600">
                Batal
            </a>
        </div>

    </form>
</div>
@endsection
