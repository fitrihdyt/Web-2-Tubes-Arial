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
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [HotelController::class, 'dashboard'])->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::middleware(IsHotelAdmin::class)->group(function () {
        Route::get('/hotels/create', [HotelController::class, 'create'])->name('hotels.create');
        Route::post('/hotels', [HotelController::class, 'store'])->name('hotels.store');
        Route::get('/hotels/{hotel}/edit', [HotelController::class, 'edit'])->name('hotels.edit');
        Route::put('/hotels/{hotel}', [HotelController::class, 'update'])->name('hotels.update');
        Route::delete('/hotels/{hotel}', [HotelController::class, 'destroy'])->name('hotels.destroy');
        
        Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
        Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
        Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
        Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
        Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');

        Route::get('/admin/bookings', [BookingController::class, 'adminIndex'])->name('admin.bookings');
        Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
        Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');
        Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
        Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
    });

    Route::middleware(IsSuperAdmin::class)->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    Route::middleware(IsUser::class)->group(function () {
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create/{room}', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');
        Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
        Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
        Route::get('/my-bookings', [BookingController::class, 'history'])->name('bookings.history');
        Route::post('/bookings/{booking}/rating', [RatingController::class, 'store'])->name('ratings.store');
        Route::post('/bookings/{booking}/pay', [PaymentController::class, 'pay'])->name('bookings.pay');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
});

Route::post('/payment/midtrans-callback', [PaymentController::class, 'midtransCallback']);

require __DIR__ . '/auth.php';
