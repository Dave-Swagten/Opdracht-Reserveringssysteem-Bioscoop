<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\ReservationController;

Route::get('/', [CinemaController::class, 'index']);
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::post('/check-availability', [CinemaController::class, 'checkAvailability'])->name('cinema.check-availability');
