<?php

use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('permission')->group(function () {
    Route::get('/role/all', [PermissionController::class, "roles"])->name('role.all');
    Route::get('/group/all', [PermissionController::class, "groups"])->name('permission.group.all');
    Route::get('/list/all', [PermissionController::class, "allList"])->name('permission.list.all');
    Route::get('/{role_id}/role/all', [PermissionController::class, "rolePermissionsList"])->name('permission.role.all');
    Route::post('/{role_id}/update/role/permissions', [PermissionController::class, "updatePermissions"])->name('permission.role.update');
    Route::post('/print/permissions', [PermissionController::class, "print"])->name('permission.print');
});

?>
