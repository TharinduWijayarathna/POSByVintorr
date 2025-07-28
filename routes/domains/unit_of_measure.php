<?php

// Unit of measures

use App\Http\Controllers\UOMController;
use Illuminate\Support\Facades\Route;

Route::prefix('unit-of-measure')->group(function () {
    Route::get('/units-all', [UOMController::class, 'all'])->name('units.all');
    Route::post('/units-store', [UOMController::class, 'store'])->name('units.store');
    Route::delete('/{uom_id}/units-delete', [UOMController::class, 'delete'])->name('units.delete');
    Route::get('/{uom_id}/units-get', [UOMController::class, 'get'])->name('units.get');
    Route::post('/{uom_id}/units-update', [UOMController::class, 'update'])->name('units.update');
});
