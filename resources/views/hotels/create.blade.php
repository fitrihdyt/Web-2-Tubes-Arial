@extends('layouts.app')

@push('styles')
<link rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')

<div class="max-w-5xl mx-auto px-4">

    {{-- HEADER --}}
    <div class="flex items-start justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Hotel</h1>
            <p class="text-sm text-gray-500 mt-1">
                Lengkapi informasi hotel dengan benar
            </p>
        </div>

        <a href="{{ route('hotels.index') }}"
           class="text-sm text-gray-500 hover:underline mt-1">
            Kembali
        </a>
    </div>

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('hotels.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-white rounded-2xl shadow p-6 space-y-8">
        @csrf

        {{-- INFO --}}
        <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-700">
                Informasi Dasar
            </h2>

            <input type="text"
                   name="name"
                   value="{{ old('name') }}"
                   placeholder="Nama Hotel"
                   class="w-full border rounded-xl px-4 py-2.5"
                   required>

            <textarea name="description"
                      rows="4"
                      placeholder="Deskripsi Hotel"
                      class="w-full border rounded-xl px-4 py-2.5">{{ old('description') }}</textarea>

            <input type="text"
                   name="address"
                   value="{{ old('address') }}"
                   placeholder="Alamat Lengkap"
                   class="w-full border rounded-xl px-4 py-2.5"
                   required>

            <input type="text"
                   name="city"
                   value="{{ old('city') }}"
                   placeholder="Kota"
                   class="w-full border rounded-xl px-4 py-2.5"
                   required>
        </div>

        {{--  STAR  --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Bintang Hotel
            </label>
            <select name="star"
                    class="w-full border rounded-xl px-4 py-2.5"
                    required>
                <option value="">Pilih Bintang</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ old('star') == $i ? 'selected' : '' }}>
                        {{ $i }} Bintang
                    </option>
                @endfor
            </select>
        </div>

        {{--  MAP  --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Lokasi Hotel
            </label>

            <div id="map"
                 data-lat="{{ old('latitude', -6.200000) }}"
                 data-lng="{{ old('longitude', 106.816666) }}"
                 class="w-full h-72 rounded-2xl border"></div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <input type="text"
                       id="latitude"
                       name="latitude"
                       value="{{ old('latitude') }}"
                       readonly
                       class="border rounded-xl px-4 py-2 bg-gray-50 text-sm">

                <input type="text"
                       id="longitude"
                       name="longitude"
                       value="{{ old('longitude') }}"
                       readonly
                       class="border rounded-xl px-4 py-2 bg-gray-50 text-sm">
            </div>
        </div>

        {{--  FASILITAS  --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">
                Fasilitas Hotel
            </label>

            @php $oldFacilities = old('facilities', []); @endphp

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($facilities as $facility)
                    <div class="space-y-2 bg-gray-50 p-3 rounded-xl">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox"
                                   name="facilities[]"
                                   value="{{ $facility->id }}"
                                   class="facility-checkbox"
                                   data-other="{{ $facility->name === 'Other' ? '1' : '0' }}"
                                   {{ in_array($facility->id, $oldFacilities) ? 'checked' : '' }}>
                            {{ $facility->name }}
                        </label>

                        @if ($facility->name === 'Other')
                            <input type="text"
                                   name="custom_facilities[{{ $facility->id }}]"
                                   value="{{ old('custom_facilities.' . $facility->id) }}"
                                   placeholder="Tulis fasilitas lain"
                                   class="other-input w-full border rounded-xl px-3 py-2 text-sm
                                   {{ in_array($facility->id, $oldFacilities) ? '' : 'hidden' }}">
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{--  IMAGES  --}}
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Thumbnail Hotel
                </label>
                <input type="file"
                       name="thumbnail"
                       class="w-full border rounded-xl px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Galeri Gambar
                </label>
                <input type="file"
                       name="images[]"
                       multiple
                       class="w-full border rounded-xl px-3 py-2 text-sm">
            </div>
        </div>

        {{--  ACTION  --}}
        <div class="flex gap-3 pt-4">
            <button type="submit"
                    class="bg-[#134662] hover:bg-[#0f3a4e]
                           text-white px-6 py-2.5 rounded-xl font-semibold">
                Simpan Hotel
            </button>

            <a href="{{ route('hotels.index') }}"
               class="px-6 py-2.5 rounded-xl border text-gray-600">
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
    if (!mapEl) return;

    const lat = Number(mapEl.dataset.lat);
    const lng = Number(mapEl.dataset.lng);

    const map = L.map('map').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    function update(latlng) {
        latInput.value = latlng.lat.toFixed(7);
        lngInput.value = latlng.lng.toFixed(7);
    }

    update({ lat, lng });

    marker.on('dragend', e => update(e.target.getLatLng()));

    map.on('click', e => {
        marker.setLatLng(e.latlng);
        update(e.latlng);
    });

    // fasilitas other
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
