<?php

// Order Items

use App\Http\Controllers\PosCustomOrderController;
use App\Http\Controllers\PosCustomOrderItemController;
use Illuminate\Support\Facades\Route;

Route::prefix('order-item')->group(function () {
    Route::post('/{order_id}/store', [PosCustomOrderItemController::class, 'store'])->name('customOrderItem.store');
    Route::get('/{order_id}/all', [PosCustomOrderItemController::class, 'all'])->name('customOrderItem.all');
    Route::delete('/{custom_order_item_id}/delete', [PosCustomOrderItemController::class, 'delete'])->name('customOrderItem.delete');
    Route::get('/{custom_order_item_id}/edit', [PosCustomOrderItemController::class, 'edit'])->name('customOrderItem.edit');
    Route::get('/{custom_order_item_id}/get', [PosCustomOrderItemController::class, 'get'])->name('customOrderItem.get');
    Route::post('/{custom_order_item_id}/update', [PosCustomOrderItemController::class, 'update'])->name('customOrderItem.update');

    Route::get('/{order_id}/approve', [PosCustomOrderController::class, 'approve'])->name('customOrder.approve');
    Route::get('/{order_id}/reject', [PosCustomOrderController::class, 'reject'])->name('customOrder.reject');
    Route::post('/{order_id}/reverse', [PosCustomOrderController::class, 'reverse'])->name('customOrder.reverse');
});
