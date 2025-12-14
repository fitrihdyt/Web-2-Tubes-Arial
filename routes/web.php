<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('hotels', HotelController::class);


