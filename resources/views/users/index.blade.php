@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-10">

{{-- HEADER --}}
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Users</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar seluruh user yang terdaftar</p>
    </div>
</div>

{{-- TABLE --}}
<div class="bg-white rounded-3xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600">
            <tr>
                <th class="px-6 py-4 text-left">Nama</th>
                <th class="px-6 py-4 text-left">Email</th>
                <th class="px-6 py-4 text-left">Role</th>
                <th class="px-6 py-4 text-left">Hotel</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $user->name }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $user->email }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($user->role === 'super_admin') bg-purple-100 text-purple-700
                            @elseif($user->role === 'hotel_admin') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst(str_replace('_',' ', $user->role)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $user->hotel->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($user->role !== 'super_admin')
                            <form action="{{ route('users.destroy', $user) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                                               bg-red-600 hover:bg-red-700 text-white text-xs font-semibold">

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m3-3h4a1 1 0 011 1v1H9V5a1 1 0 011-1z" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        @else
                            <span class="text-gray-400 text-xs">Tidak tersedia</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


</div>
@endsection
