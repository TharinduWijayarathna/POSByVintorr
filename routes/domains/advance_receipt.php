<?php

//Advanced Receipts

use App\Http\Controllers\PosAdvanceReceiptController;
use Illuminate\Support\Facades\Route;

Route::prefix('advance-receipt')->group(function () {
    Route::get('/', [PosAdvanceReceiptController::class, "index"])->name('advanceReceipt.index');
    Route::post('/{order_id}/store', [PosAdvanceReceiptController::class, "store"])->name('advanceReceipt.store');
    Route::get('/all', [PosAdvanceReceiptController::class, "all"])->name('advanceReceipt.all');
    Route::delete('/{receipt_id}/delete', [PosAdvanceReceiptController::class, "delete"])->name('advanceReceipt.delete');
    Route::get('/{receipt_id}/edit', [PosAdvanceReceiptController::class, "edit"])->name('advanceReceipt.edit');
    Route::get('/{receipt_id}/get', [PosAdvanceReceiptController::class, "get"])->name('advanceReceipt.get');
    Route::post('/{receipt_id}/update', [PosAdvanceReceiptController::class, "update"])->name('advanceReceipt.update');
    Route::get('/{order_id}/print', [PosAdvanceReceiptController::class, "print"])->name('advanceReceipt.print');
    Route::post('/{order_id}/print', [PosAdvanceReceiptController::class, "printPaymentWise"])->name('advanceReceipt.print.payment-wise');
    Route::get('/{receipt_id}/list', [PosAdvanceReceiptController::class, "list"])->name('advanceReceipt.list');
});

?>
