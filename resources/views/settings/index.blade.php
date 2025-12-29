@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">
    <h2 class="text-2xl font-bold mb-4">⚙️ Settings Akun</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold">Nama</label>
            <input type="text" name="name" value="{{ auth()->user()->name }}"
                class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-semibold">No HP</label>
            <input type="text" name="phone" value="{{ auth()->user()->phone }}"
                class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Foto Profil</label>
            <input type="file" name="avatar" class="w-full">
        </div>

        @if(auth()->user()->avatar)
            <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                class="w-24 h-24 rounded-full mb-4">
        @endif

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection
