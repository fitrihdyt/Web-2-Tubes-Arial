@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<h1 class="text-2xl font-bold mb-6">Tambah Hotel</h1>

<form action="{{ route('hotels.store') }}" 
      method="POST"
      enctype="multipart/form-data"
      class="bg-white p-6 rounded-xl shadow space-y-4">
    @csrf

    {{-- BASIC INFO --}}
    <input type="text"
           name="name"
           value="{{ old('name') }}"
           placeholder="Nama Hotel"
           class="w-full border rounded p-2"
           required>

    <textarea name="description"
              placeholder="Deskripsi"
              class="w-full border rounded p-2">{{ old('description') }}</textarea>

    <input type="text"
           name="address"
           value="{{ old('address') }}"
           placeholder="Alamat"
           class="w-full border rounded p-2">

    <input type="text"
           name="city"
           value="{{ old('city') }}"
           placeholder="Kota"
           class="w-full border rounded p-2"
           required>

    {{-- STAR --}}
    <div>
        <label class="block font-medium mb-1">Bintang Hotel</label>
        <select name="star" class="w-full border rounded p-2" required>
            <option value="">Pilih Bintang</option>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}"
                    {{ old('star') == $i ? 'selected' : '' }}>
                    {{ $i }} Bintang
                </option>
            @endfor
        </select>
    </div>

    {{-- MAP --}}
    <div>
        <label class="block font-medium mb-2">Lokasi Hotel</label>
        <div id="map" class="w-full h-72 rounded-lg border"></div>

        <div class="grid grid-cols-2 gap-4 mt-3">
            <input type="text"
                   name="latitude"
                   id="latitude"
                   value="{{ old('latitude') }}"
                   placeholder="Latitude"
                   class="border rounded p-2"
                   readonly>

            <input type="text"
                   name="longitude"
                   id="longitude"
                   value="{{ old('longitude') }}"
                   placeholder="Longitude"
                   class="border rounded p-2"
                   readonly>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- FASILITAS HOTEL (LENGKAP) --}}
    {{-- ========================= --}}
    <div>
        <label class="block font-medium mb-2">Fasilitas Hotel</label>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach ($facilities as $facility)
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox"
                               name="facilities[]"
                               value="{{ $facility->id }}"
                               class="facility-checkbox"
                               data-other="{{ $facility->name === 'Other' ? '1' : '0' }}"
                               {{ in_array($facility->id, old('facilities', [])) ? 'checked' : '' }}>
                        {{ $facility->name }}
                    </label>

                    @if ($facility->name === 'Other')
                        <input type="text"
                               name="custom_facilities[{{ $facility->id }}]"
                               value="{{ old('custom_facilities.' . $facility->id) }}"
                               placeholder="Tulis fasilitas lain"
                               class="other-input w-full border rounded p-2 mt-2
                               {{ in_array($facility->id, old('facilities', [])) ? '' : 'hidden' }}">
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- THUMBNAIL --}}
    <div>
        <label class="block font-medium mb-1">Thumbnail</label>
        <input type="file"
               name="thumbnail"
               class="w-full border rounded p-2">
    </div>

    {{-- IMAGES --}}
    <div>
        <label class="block font-medium mb-1">Galeri Gambar</label>
        <input type="file"
               name="images[]"
               multiple
               class="w-full border rounded p-2">
    </div>

    {{-- ACTION --}}
    <div class="flex gap-3">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Simpan
        </button>
        <a href="{{ route('hotels.index') }}"
           class="px-4 py-2 rounded-lg border">
            Batal
        </a>
    </div>
</form>

{{-- ========================= --}}
{{-- SCRIPT MAP + FASILITAS --}}
{{-- ========================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ================= MAP ================= */
    const map = L.map('map').setView([-6.200000, 106.816666], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const marker = L.marker([-6.200000, 106.816666], {
        draggable: true
    }).addTo(map);

    function updateLatLng(latlng) {
        document.getElementById('latitude').value = latlng.lat.toFixed(7);
        document.getElementById('longitude').value = latlng.lng.toFixed(7);
    }

    marker.on('dragend', e => updateLatLng(e.target.getLatLng()));

    map.on('click', e => {
        marker.setLatLng(e.latlng);
        updateLatLng(e.latlng);
    });

    /* ================= FASILITAS OTHER ================= */
    document.querySelectorAll('.facility-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            if (this.dataset.other === '1') {
                const input = this.closest('div').querySelector('.other-input');
                input.classList.toggle('hidden', !this.checked);
            }
        });
    });

});
</script>
@endsection
