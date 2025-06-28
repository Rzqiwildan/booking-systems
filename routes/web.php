<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookingController::class, 'index'])->name('booking');
Route::post('/booking', [BookingController::class, 'submitForm'])->name('form.submit');