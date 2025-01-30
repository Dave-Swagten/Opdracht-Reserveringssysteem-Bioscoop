<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CinemaController;

Route::get('/', [CinemaController::class, 'index']);
