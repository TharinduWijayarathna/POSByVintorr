<?php

//Return cart

use App\Http\Controllers\ReturnsController;
use Illuminate\Support\Facades\Route;

Route::prefix('returns')->group(function () {
    Route::get('/view', [ReturnsController::class, "view"])->name('return.view');
    Route::get('/all', [ReturnsController::class, "all"])->name('return.bill.all');
    Route::get('/', [ReturnsController::class, "index"])->name('return.index');
    Route::get('/return-order-get', [ReturnsController::class, "returnOrder"])->name('return.order');
    Route::get('/{order_id}/return-edit-get', [ReturnsController::class, "returnEditOrder"])->name('return.edit.order');
    Route::get('/{order_id}/process', [ReturnsController::class, "process"])->name('cart.return.process');
    Route::post('/{order_code}/getOrder', [ReturnsController::class, "getOrder"])->name('get.order');
    Route::post('/{order_code}/get', [ReturnsController::class, "get"])->name('get.order.products');
    Route::post('/bill', [ReturnsController::class, "returnBill"])->name('cart.return.bill');
    Route::get('/{order_id}/edit-return', [ReturnsController::class, "editPage"])->name('return.edit');

    Route::post('/return-customer/{order_id?}', [ReturnsController::class, "setCustomer"])->name('return.customer');
    Route::get('/{order_id}/remove-return-customer', [ReturnsController::class, "removeCustomerId"])->name('return.customer.remove');
    Route::post('/add/return-items/{order_id?}', [ReturnsController::class, "addItems"])->name('return.item.store');
    Route::get('/get-returns/{order_id?}', [ReturnsController::class, "getReturnProduct"])->name('return.get.products');
    Route::delete('/{id}/delete-item', [ReturnsController::class, "deleteItem"])->name('return.delete.product');
    Route::get('/get-return-total/{order_id?}', [ReturnsController::class, "getTotals"])->name('return.get.total');
    Route::post('/return-done', [ReturnsController::class, "returnDone"])->name('return.done');
    Route::post('/return-update', [ReturnsController::class, "returnUpdate"])->name('return.update');
    Route::delete('/{order_id}/delete', [ReturnsController::class, "delete"])->name('return.delete');
    Route::get('/deleted-list', [ReturnsController::class, "deletedList"])->name('return.deleted.list');
    Route::get('/all-deleted', [ReturnsController::class, "deletedAll"])->name('return.deleted.all');
    Route::get('/{order_id}/restore', [ReturnsController::class, "restoreReturn"])->name('return.restore');
});
