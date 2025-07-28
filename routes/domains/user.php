<?php

// User

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::get('/all', [UserController::class, 'all'])->name('user.all');
    Route::get('/all-users', [UserController::class, 'allUsers'])->name('user.multiselect.all');
    Route::get('/all-admins', [UserController::class, 'allAdmins'])->name('admin.multiselect.all');
    Route::post('/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/{user_id}/get', [UserController::class, 'get'])->name('user.get');
    Route::get('/{user_id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/{user_id}/update', [UserController::class, 'update'])->name('user.update');
    Route::delete('/{user_id}/delete', [UserController::class, 'delete'])->name('user.delete');
    Route::get('/{user_id}/restore', [UserController::class, 'restore'])->name('user.restore');
    Route::post('/user-feedback/send', [UserController::class, 'sendUserFeedback'])->name('user.feedback.send');
});
