<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('role')->group(function () {
    Route::get('/', [RoleController::class, "index"])->name('role.index');
    Route::get('/{role_id}/edit', [RoleController::class, "edit"])->name('role.edit');
    Route::get('/{role_id}/get', [RoleController::class, "get"])->name('role.get');
});

?>
