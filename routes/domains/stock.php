<?php

// Stock

use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

Route::prefix('stock')->group(function () {
    Route::get('/', [StockController::class, 'index'])->name('stock.index');
    Route::get('/all', [StockController::class, 'all'])->name('stocks.all');
    Route::get('/{product_id}/get', [StockController::class, 'get'])->name('stock.get');
    // Route::post('/{product_id}/update', [StockController::class, "update"])->name('product.update');
    Route::post('/export/stock/excel', [StockController::class, 'stockExport'])->name('product.stock.export');
});
