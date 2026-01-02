@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 space-y-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Room
        </h1>
        <a href="{{ route('rooms.index') }}"
           class="text-sm text-gray-500 hover:underline">
            Kembali
        </a>
    </div>

    <form action="{{ route('rooms.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-white rounded-2xl shadow p-6 space-y-8">
        @csrf

        {{-- ================= BASIC INFO ================= --}}
        <div class="grid md:grid-cols-2 gap-6">

            {{-- HOTEL --}}
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">
                    Hotel
                </label>

                {{-- ID hotel dikirim diam-diam --}}
                <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

                {{-- Nama hotel cuma buat ditampilkan --}}
                <input type="text"
                    value="{{ $hotel->name }}"
                    class="mt-1 w-full border rounded-xl px-4 py-2 bg-gray-100 text-gray-600"
                    readonly>
            </div>

            {{-- ROOM NAME --}}
            <div>
                <label class="text-sm font-medium text-gray-600">
                    Nama Room
                </label>
                <input type="text"
                       name="name"
                       placeholder="Contoh: Deluxe Room"
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
                       placeholder="500000"
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
                       placeholder="2"
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
                       placeholder="10"
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
                          placeholder="Deskripsi singkat kamar"
                          class="mt-1 w-full border rounded-xl px-4 py-2"></textarea>
            </div>
        </div>

        {{-- ================= THUMBNAIL ================= --}}
        <div>
            <label class="text-sm font-medium text-gray-600 block mb-2">
                Thumbnail Room
            </label>
            <input type="file"
                   name="thumbnail"
                   class="w-full border rounded-xl px-4 py-2 text-sm">
        </div>

        {{-- ================= GALLERY ================= --}}
        <div>
            <label class="text-sm font-medium text-gray-600 block mb-2">
                Galeri Room
            </label>
            <input type="file"
                   name="images[]"
                   multiple
                   class="w-full border rounded-xl px-4 py-2 text-sm">
        </div>

        {{-- ================= ACTION ================= --}}
        <div class="flex gap-4 pt-4">
            <button class="bg-[#134662] hover:bg-[#0f3a4e]
                           text-white px-6 py-3 rounded-xl font-semibold">
                Simpan Room
            </button>

            <a href="{{ route('rooms.index') }}"
               class="px-6 py-3 rounded-xl border text-gray-600">
                Batal
            </a>
        </div>

    </form>
</div>
@endsection
