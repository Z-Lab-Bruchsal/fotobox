<?php

use App\Http\Controllers\PhotoController;
use App\Http\Controllers\SettingsController;
use App\Http\Middleware\SettingsPassword;
use Illuminate\Support\Facades\Route;

Route::get('/', [PhotoController::class, 'index'])
    ->middleware(\App\Http\Middleware\RestrictToSubnet::class)
    ->name('camera');

Route::get('/settings/login', [SettingsController::class, 'login'])->name('settings.login');
Route::post('/settings/login', [SettingsController::class, 'authenticate'])->name('settings.authenticate');
Route::post('/settings/logout', [SettingsController::class, 'logout'])->name('settings.logout');

Route::get('/settings', [SettingsController::class, 'index'])
    ->middleware(SettingsPassword::class)
    ->name('settings');
