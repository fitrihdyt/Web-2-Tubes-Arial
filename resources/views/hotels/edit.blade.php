@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Hotel</h1>

<form action="{{ route('hotels.update', $hotel) }}"
      method="POST"
      enctype="multipart/form-data"
      class="bg-white p-6 rounded-xl shadow space-y-4">
    @csrf
    @method('PUT')

    <input type="text" name="name"
           value="{{ $hotel->name }}"
           class="w-full border rounded p-2" required>

    <textarea name="description"
              class="w-full border rounded p-2">{{ $hotel->description }}</textarea>

    <input type="text" name="address"
           value="{{ $hotel->address }}"
           class="w-full border rounded p-2">

    <input type="text" name="city"
           value="{{ $hotel->city }}"
           class="w-full border rounded p-2" required>

    <!-- MAP -->
    <div>
        <label class="block font-medium mb-2">Lokasi Hotel</label>
        <div id="map" class="w-full h-72 rounded-lg border"></div>

        <div class="grid grid-cols-2 gap-4 mt-3">
            <input type="text" name="latitude" id="latitude"
                   value="{{ $hotel->latitude }}"
                   class="border rounded p-2" readonly>

            <input type="text" name="longitude" id="longitude"
                   value="{{ $hotel->longitude }}"
                   class="border rounded p-2" readonly>
        </div>
    </div>

    <!-- Thumbnail -->
    <div>
        <label class="block mb-1 font-medium">Thumbnail Saat Ini</label>
        @if($hotel->thumbnail)
            <img src="{{ asset('storage/' . $hotel->thumbnail) }}"
                 class="w-32 h-20 object-cover rounded mb-2">
        @endif

        <input type="file" name="thumbnail"
               class="w-full border rounded p-2">
        <p class="text-sm text-gray-500">Kosongkan jika tidak diganti</p>
    </div>

    <!-- Images -->
    <div>
        <label class="block mb-1 font-medium">Galeri Saat Ini</label>
        <div class="flex gap-2 flex-wrap mb-2">
            @if($hotel->images)
                @foreach($hotel->images as $img)
                    <img src="{{ asset('storage/' . $img) }}"
                         class="w-24 h-16 object-cover rounded">
                @endforeach
            @endif
        </div>

        <input type="file" name="images[]" multiple
               class="w-full border rounded p-2">
        <p class="text-sm text-gray-500">
            Gambar baru akan ditambahkan ke galeri
        </p>
    </div>

    <div class="flex gap-3">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Update
        </button>

        <a href="{{ route('hotels.index') }}"
           class="px-4 py-2 rounded-lg border">
            Batal
        </a>
    </div>
</form>

<<script>
document.addEventListener('DOMContentLoaded', function () {

    const lat = @json($hotel->latitude ?? -6.200000);
    const lng = @json($hotel->longitude ?? 106.816666);

    const map = L.map('map').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const marker = L.marker([lat, lng], {
        draggable: true
    }).addTo(map);

    marker.on('dragend', function () {
        const pos = marker.getLatLng();
        document.getElementById('latitude').value = pos.lat.toFixed(7);
        document.getElementById('longitude').value = pos.lng.toFixed(7);
    });

    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        document.getElementById('latitude').value = e.latlng.lat.toFixed(7);
        document.getElementById('longitude').value = e.latlng.lng.toFixed(7);
    });

});
</script>

@endsection
