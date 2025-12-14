@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Tambah Room</h1>

<form action="{{ route('rooms.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="bg-white p-6 rounded-xl shadow space-y-4">
    @csrf

    <select name="hotel_id" class="w-full border rounded p-2" required>
        <option value="">Pilih Hotel</option>
        @foreach ($hotels as $hotel)
            <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
        @endforeach
    </select>

    <input type="text" name="name" placeholder="Nama Room"
           class="w-full border rounded p-2" required>

    <input type="number" name="price" placeholder="Harga / malam"
           class="w-full border rounded p-2" required>

    <div class="grid grid-cols-2 gap-4">
        <input type="number" name="capacity" placeholder="Kapasitas"
               class="border rounded p-2" required>
        <input type="number" name="stock" placeholder="Stok"
               class="border rounded p-2" required>
    </div>

    <textarea name="description" placeholder="Deskripsi"
              class="w-full border rounded p-2"></textarea>

    <div>
        <label class="block font-medium mb-1">Thumbnail</label>
        <input type="file" name="thumbnail"
               class="w-full border rounded p-2">
    </div>

    <div>
        <label class="block font-medium mb-1">Galeri Gambar</label>
        <input type="file" name="images[]" multiple
               class="w-full border rounded p-2">
    </div>

    <div class="flex gap-3">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">Simpan</button>
        <a href="{{ route('rooms.index') }}" class="px-4 py-2 rounded-lg border">Batal</a>
    </div>
</form>
@endsection
