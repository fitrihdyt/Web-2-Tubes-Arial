@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Daftar Hotel</h1>

    @auth
        @if(in_array(auth()->user()->role, ['super_admin', 'hotel_admin']))
            <a href="{{ route('hotels.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                + Tambah Hotel
            </a>
        @endif
    @endauth
</div>

@if($hotels->isEmpty())
    <div class="bg-white p-6 rounded-lg shadow text-center text-gray-500">
        Belum ada data hotel
    </div>
@else
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
@foreach($hotels as $hotel)
    <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">

        <!-- CLICKABLE AREA -->
        <a href="{{ route('hotels.show', $hotel) }}" class="block hover:bg-gray-50">

            <!-- Thumbnail -->
            <div class="h-44 bg-gray-100">
                @if($hotel->thumbnail)
                    <img src="{{ asset('storage/' . $hotel->thumbnail) }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        No Image
                    </div>
                @endif
            </div>

            <!-- Content -->
            <div class="p-4">
                <h2 class="text-xl font-semibold">{{ $hotel->name }}</h2>

                <!-- ⭐ STAR -->
                <div class="flex items-center gap-1 text-yellow-500 text-sm mt-1">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $hotel->star)
                            ★
                        @else
                            <span class="text-gray-300">★</span>
                        @endif
                    @endfor
                    <span class="text-gray-400 ml-1">({{ $hotel->star }})</span>
                </div>

                <p class="text-gray-500 mt-1">{{ $hotel->city }}</p>

                <p class="text-sm text-gray-600 mt-2">
                    {{ \Illuminate\Support\Str::limit($hotel->description, 80) }}
                </p>
            </div>
        </a>

        <!-- FOOTER (ADMIN ONLY) -->
        @auth
        @if(in_array(auth()->user()->role, ['super_admin', 'hotel_admin']))
        <div class="flex justify-between items-center px-4 py-3 border-t bg-white">
            <a href="{{ route('hotels.edit', $hotel) }}"
               class="text-yellow-600 hover:underline">
                Edit
            </a>

            <form action="{{ route('hotels.destroy', $hotel) }}"
                  method="POST"
                  onsubmit="return confirm('Yakin hapus hotel ini?')">
                @csrf
                @method('DELETE')
                <button class="text-red-600 hover:underline">
                    Hapus
                </button>
            </form>
        </div>
        @endif
        @endauth

    </div>
@endforeach
</div>
@endif
@endsection
