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

    <input type="text" name="name" placeholder="Nama Hotel"
           class="w-full border rounded p-2" required>

    <textarea name="description" placeholder="Deskripsi"
              class="w-full border rounded p-2"></textarea>

    <input type="text" name="address" placeholder="Alamat"
           class="w-full border rounded p-2">

    <input type="text" name="city" placeholder="Kota"
           class="w-full border rounded p-2" required>

    <div>
        <label class="block font-medium mb-1">Bintang Hotel</label>
        <select name="star" class="w-full border rounded p-2" required>
            <option value="">Pilih Bintang</option>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }} Bintang</option>
            @endfor
        </select>
    </div>

    <!-- MAP -->
    <div>
        <label class="block font-medium mb-2">Lokasi Hotel</label>
        <div id="map" class="w-full h-72 rounded-lg border"></div>

        <div class="grid grid-cols-2 gap-4 mt-3">
            <input type="text" name="latitude" id="latitude"
                   placeholder="Latitude"
                   class="border rounded p-2" readonly>

            <input type="text" name="longitude" id="longitude"
                   placeholder="Longitude"
                   class="border rounded p-2" readonly>
        </div>
    </div>

    <!-- Thumbnail -->
    <div>
        <label class="block font-medium mb-1">Thumbnail</label>
        <input type="file" name="thumbnail" class="w-full border rounded p-2">
    </div>

    <!-- Images -->
    <div>
        <label class="block font-medium mb-1">Galeri Gambar</label>
        <input type="file" name="images[]" multiple class="w-full border rounded p-2">
    </div>

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

<script>
document.addEventListener('DOMContentLoaded', function () {

    const map = L.map('map').setView([-6.200000, 106.816666], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // SEARCH LOKASI
    const geocoder = L.Control.geocoder({
        defaultMarkGeocode: false
    })
    .on('markgeocode', function(e) {
        const latlng = e.geocode.center;
        marker.setLatLng(latlng);
        map.setView(latlng, 15);

        latitude.value = latlng.lat.toFixed(7);
        longitude.value = latlng.lng.toFixed(7);
    })
    .addTo(map);

    // MARKER DRAGGABLE
    const marker = L.marker([-6.200000, 106.816666], {
        draggable: true
    }).addTo(map);

    marker.on('dragend', function (e) {
        const pos = marker.getLatLng();
        latitude.value = pos.lat.toFixed(7);
        longitude.value = pos.lng.toFixed(7);
    });

    // KLIK MAP
    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        latitude.value = e.latlng.lat.toFixed(7);
        longitude.value = e.latlng.lng.toFixed(7);
    });

});
</script>
@endsection
