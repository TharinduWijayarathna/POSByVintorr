<?php

// Receipts / Bills

use App\Http\Controllers\PosReceiptController;
use Illuminate\Support\Facades\Route;

Route::prefix('bills')->group(function () {
    Route::get('/view', [PosReceiptController::class, 'index'])->name('receipt.index');
    Route::get('/credit', [PosReceiptController::class, 'credit'])->name('receipt.credit');
    Route::get('/all', [PosReceiptController::class, 'all'])->name('cart.bill.all');

    Route::get('/credit-orders-all', [PosReceiptController::class, 'creditAll'])->name('credit.order.all');
    Route::get('/{order_id}/credit-bills-all', [PosReceiptController::class, 'bills'])->name('credit.bill.all');
    Route::post('/{order_id}/payment-bill', [PosReceiptController::class, 'creditPay'])->name('payment.credit.bill');
    Route::post('/update-bill', [PosReceiptController::class, 'updateCreditPay'])->name('payment.bill.update');
    Route::get('/credit/{order_id}/edit', [PosReceiptController::class, 'edit'])->name('credit.edit');
});
