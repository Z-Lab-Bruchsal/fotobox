<?php

use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PhotoController::class, 'index'])
    ->middleware(\App\Http\Middleware\RestrictToSubnet::class)
    ->name('camera');
