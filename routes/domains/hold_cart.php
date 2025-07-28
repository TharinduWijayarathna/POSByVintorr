<?php

// Hold Cart

use App\Http\Controllers\HoldCartController;
use Illuminate\Support\Facades\Route;

Route::prefix('saved-order')->group(function () {
    Route::get('/', [HoldCartController::class, 'index'])->name('cart.hold.index');
    Route::get('/all', [HoldCartController::class, 'all'])->name('cart.hold.all');
    Route::get('/get', [HoldCartController::class, 'edit'])->name('cart.item.edit');
    Route::get('/{order_id}/order/reactive', [HoldCartController::class, 'reActive'])->name('cart.order.reactive');
    Route::get('/{order_id}/order/delete', [HoldCartController::class, 'deleteOrder'])->name('order.delete');
});
