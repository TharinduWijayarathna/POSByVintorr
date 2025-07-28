<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('profile')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/{user_id}/get', [ProfileController::class, 'get'])->name('myDetails.get');
    Route::post('/{user_id}/update-profile', [ProfileController::class, 'profileUpdate'])->name('profile.update');
    Route::get('/sessions', [ProfileController::class, 'getSessions'])->name('get.sessions');
});
