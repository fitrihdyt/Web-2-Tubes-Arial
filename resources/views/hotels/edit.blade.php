@extends('layouts.app')

@push('styles')
<link rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

@section('content')
<div class="max-w-5xl mx-auto px-4 space-y-8">

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            {{-- ICON --}}
            <div class="p-2 bg-gray-100 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5 text-gray-700"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M8 21h8M12 17V3m0 0L5 9m7-6l7 6"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">
                Edit Hotel
            </h1>
        </div>

        <a href="{{ route('hotels.index') }}"
           class="text-sm text-gray-500 hover:underline">
            Kembali
        </a>
    </div>

    <form action="{{ route('hotels.update', $hotel) }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-white rounded-2xl shadow p-6 space-y-8">
        @csrf
        @method('PUT')

        {{-- ================= BASIC INFO ================= --}}
        <div class="grid md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm font-medium text-gray-600">Nama Hotel</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $hotel->name) }}"
                       class="mt-1 w-full border rounded-xl px-4 py-2"
                       required>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Kota</label>
                <input type="text"
                       name="city"
                       value="{{ old('city', $hotel->city) }}"
                       class="mt-1 w-full border rounded-xl px-4 py-2"
                       required>
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">Alamat</label>
                <input type="text"
                       name="address"
                       value="{{ old('address', $hotel->address) }}"
                       class="mt-1 w-full border rounded-xl px-4 py-2">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">Deskripsi</label>
                <textarea name="description"
                          rows="4"
                          class="mt-1 w-full border rounded-xl px-4 py-2">{{ old('description', $hotel->description) }}</textarea>
            </div>
        </div>

        {{-- ================= STAR ================= --}}
        <div>
            <label class="text-sm font-medium text-gray-600">Bintang Hotel</label>
            <select name="star"
                    class="mt-1 w-full border rounded-xl px-4 py-2"
                    required>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}"
                        {{ old('star', $hotel->star) == $i ? 'selected' : '' }}>
                        {{ $i }} Bintang
                    </option>
                @endfor
            </select>
        </div>

        {{-- ================= MAP ================= --}}
        <div>
            <label class="flex items-center gap-2 text-sm font-medium text-gray-600 mb-2">
                {{-- LOCATION SVG --}}
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 text-gray-600"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 11a3 3 0 100-6 3 3 0 000 6z"/>
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M19.5 11c0 7-7.5 11-7.5 11S4.5 18 4.5 11a7.5 7.5 0 1115 0z"/>
                </svg>
                Lokasi Hotel
            </label>

            <div id="map"
                 data-lat="{{ old('latitude', $hotel->latitude ?? -6.200000) }}"
                 data-lng="{{ old('longitude', $hotel->longitude ?? 106.816666) }}"
                 class="w-full h-72 rounded-3xl border"></div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <input type="text"
                       id="latitude"
                       name="latitude"
                       value="{{ old('latitude', $hotel->latitude) }}"
                       class="border rounded-xl px-4 py-2 text-sm"
                       readonly>

                <input type="text"
                       id="longitude"
                       name="longitude"
                       value="{{ old('longitude', $hotel->longitude) }}"
                       class="border rounded-xl px-4 py-2 text-sm"
                       readonly>
            </div>
        </div>

        {{-- ================= FASILITAS ================= --}}
        <div>
            <label class="flex items-center gap-2 text-sm font-medium text-gray-600 mb-3">
                {{-- CHECK SVG --}}
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 text-gray-600"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>
                Fasilitas Hotel
            </label>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($facilities as $facility)
                    @php
                        $selected = $hotel->facilities->firstWhere('id', $facility->id);
                        $checked = in_array(
                            $facility->id,
                            old('facilities', $hotel->facilities->pluck('id')->toArray())
                        );
                    @endphp

                    <div class="bg-gray-50 p-3 rounded-xl">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox"
                                   name="facilities[]"
                                   value="{{ $facility->id }}"
                                   class="facility-checkbox"
                                   data-other="{{ $facility->name === 'Other' ? '1' : '0' }}"
                                   {{ $checked ? 'checked' : '' }}>
                            {{ $facility->name }}
                        </label>

                        @if ($facility->name === 'Other')
                            <input type="text"
                                   name="custom_facilities[{{ $facility->id }}]"
                                   value="{{ old(
                                       'custom_facilities.' . $facility->id,
                                       $selected?->pivot->custom_name
                                   ) }}"
                                   placeholder="Tulis fasilitas lain"
                                   class="other-input mt-2 w-full border rounded-xl px-3 py-2 text-sm
                                   {{ $checked ? '' : 'hidden' }}">
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ================= THUMBNAIL ================= --}}
        <div>
            <label class="text-sm font-medium text-gray-600 block mb-2">
                Thumbnail Hotel
            </label>

            @if($hotel->thumbnail)
                <img src="{{ asset('storage/' . $hotel->thumbnail) }}"
                     class="w-40 h-24 object-cover rounded-xl mb-3">
            @endif

            <input type="file"
                   name="thumbnail"
                   class="w-full border rounded-xl px-4 py-2 text-sm">
        </div>

        {{-- ================= GALLERY ================= --}}
        <div>
            <label class="text-sm font-medium text-gray-600 block mb-2">
                Galeri Hotel
            </label>

            <div class="flex gap-3 flex-wrap mb-3">
                @foreach ($hotel->images ?? [] as $img)
                    <img src="{{ asset('storage/' . $img) }}"
                         class="w-28 h-20 object-cover rounded-xl">
                @endforeach
            </div>

            <input type="file"
                   name="images[]"
                   multiple
                   class="w-full border rounded-xl px-4 py-2 text-sm">
        </div>

        {{-- ================= ACTION ================= --}}
        <div class="flex gap-4 pt-4">
            <button class="bg-[#134662] hover:bg-[#0f3a4e]
                           text-white px-6 py-3 rounded-xl font-semibold">
                Update Hotel
            </button>

            <a href="{{ route('hotels.index') }}"
               class="px-6 py-3 rounded-xl border text-gray-600">
                Batal
            </a>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mapEl = document.getElementById('map');
    const lat = Number(mapEl.dataset.lat);
    const lng = Number(mapEl.dataset.lng);

    const map = L.map('map').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    function updateLatLng(latlng) {
        document.getElementById('latitude').value = latlng.lat.toFixed(7);
        document.getElementById('longitude').value = latlng.lng.toFixed(7);
    }

    updateLatLng({ lat, lng });

    marker.on('dragend', e => updateLatLng(e.target.getLatLng()));
    map.on('click', e => {
        marker.setLatLng(e.latlng);
        updateLatLng(e.latlng);
    });

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
@endpush
