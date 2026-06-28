<?php

use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.basic')->group(function () {
    Route::get('/', [PhotoController::class, 'index'])->name('camera');
});
