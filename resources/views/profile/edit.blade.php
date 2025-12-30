@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <h2 class="font-semibold text-2xl text-gray-800">
            Profile
        </h2>

        <a href="{{ route('settings.index') }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium 
                    text-white bg-blue-600 rounded-lg 
                    hover:bg-blue-700 transition">
                Settings
        </a>


        <!--@if(!auth()->user()->isAdmin)
            <a href="{{ route('bookings.history') }}"
            class="inline-block text-blue-600 hover:underline">
                Lihat Histori Booking
            </a>
        @endif-->

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>
@endsection
