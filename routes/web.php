<?php

use Illuminate\Support\Facades\Route;
use Wallacemartinss\FilamentIconPicker\Http\Controllers\IconController;
use Wallacemartinss\FilamentIconPicker\Http\Controllers\IconSvgController;

Route::middleware(['web'])->group(function () {
    Route::get('/filament-icon-picker/icons', IconController::class)
        ->name('filament-icon-picker.icons');

    Route::get('/filament-icon-picker/icon/{icon}', IconSvgController::class)
        ->name('filament-icon-picker.icon')
        ->where('icon', '.*');
});
