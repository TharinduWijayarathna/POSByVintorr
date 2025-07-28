<?php

//Categories

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('categories')->group(function () {
    Route::get('/all', [CategoryController::class, "all"])->name('categories.all');
    Route::get('/category-all', [CategoryController::class, "categoryAll"])->name('categoryDetails.all');
    Route::post('/category-store', [CategoryController::class, "store"])->name('category.store');
    Route::get('/{category_id}/get-category', [CategoryController::class, "getCategory"])->name('select.category.get');
    Route::get('/{category_id}/get', [CategoryController::class, "get"])->name('category.get');
    Route::post('/{category_id}/update', [CategoryController::class, "update"])->name('category.update');
    Route::delete('/{category_id}/delete', [CategoryController::class, "delete"])->name('category.delete');
    Route::get('/get/latest', [CategoryController::class, "getLatestCategory"])->name('category.latest.get');

    Route::get('/units-all', [CategoryController::class, "unitsAll"])->name('units.all');
    Route::get('/{unit_id}/get-unit', [CategoryController::class, "getUnit"])->name('select.unit.get');
});

?>
