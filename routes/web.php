<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ProfileController;

use App\Http\Middleware\IsHotelAdmin;
use App\Http\Middleware\IsSuperAdmin;
use App\Http\Middleware\IsUser;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [HotelController::class, 'dashboard'])->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/hotels', [HotelController::class, 'index'])->middleware(['auth', 'IsHotelAdmin'])->name('hotels.index');

Route::middleware(['auth', 'IsHotelAdmin'])->group(function () {
    Route::get('/hotels/create', [HotelController::class, 'create'])->name('hotels.create');
    Route::post('/hotels', [HotelController::class, 'store'])->name('hotels.store');
    Route::get('/hotels/{hotel}/edit', [HotelController::class, 'edit'])->name('hotels.edit');
    Route::put('/hotels/{hotel}', [HotelController::class, 'update'])->name('hotels.update');
    Route::delete('/hotels/{hotel}', [HotelController::class, 'destroy'])->name('hotels.destroy');
});

Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');

Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');

Route::middleware(['auth', 'IsHotelAdmin'])->group(function () {
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');
});

Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

Route::middleware('auth')->group(function () {

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{room}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    Route::get('/bookings/history', [BookingController::class, 'history'])
        ->name('bookings.history');

    Route::post('/bookings/{booking}/rating', [BookingController::class, 'storeRating'])
        ->name('bookings.rating');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::post('/bookings/{booking}/pay', [PaymentController::class, 'pay'])
        ->name('bookings.pay');
});

Route::middleware(['auth', 'notAdmin'])->group(function () {
    Route::get('/my-bookings', [BookingController::class, 'history'])
        ->name('bookings.history');

    Route::post('/ratings', [RatingController::class, 'store'])
        ->name('ratings.store');
});

Route::post('/payment/midtrans-callback', [PaymentController::class, 'midtransCallback']);

require __DIR__.'/auth.php';

Route::middleware(['auth', IsUser::class])->group(function () {
});

Route::middleware(['auth', IsHotelAdmin::class])->group(function () {
});

Route::middleware(['auth', IsSuperAdmin::class])->group(function () {
});