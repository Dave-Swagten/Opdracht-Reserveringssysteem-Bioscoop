<?php

use App\Http\Controllers\CinemaController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\ScreenController;
use App\Http\Controllers\Admin\ScreeningController;
use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [CinemaController::class, 'index'])->name('cinema.index');
Route::get('/check-availability', [CinemaController::class, 'checkAvailability'])->name('cinema.check-availability');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');

// Admin auth routes
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin routes (beveiligd)
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::resource('movies', MovieController::class);
    Route::resource('screens', ScreenController::class);
    Route::resource('screenings', ScreeningController::class);
});
