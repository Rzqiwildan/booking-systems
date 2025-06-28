<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;
use Filament\Facades\Filament;

Route::get('/', [BookingController::class, 'index'])->name('booking');
Route::post('/booking', [BookingController::class, 'submitForm'])->name('form.submit');
Route::middleware(['auth'])->group(function () {
    Filament::routes(); // Memanggil rute Filament
});