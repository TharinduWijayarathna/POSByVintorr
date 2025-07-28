<?php

//Transactions

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('transaction')->group(function () {
    Route::get('/', [TransactionController::class, "index"])->name('transaction.index');
    Route::get('/all', [TransactionController::class, "all"])->name('transaction.all');
    Route::post('/store', [TransactionController::class, "store"])->name('transaction.store');
    Route::get('/{transaction_id}/edit', [TransactionController::class, "edit"])->name('transaction.edit');
    Route::post('/{transaction_id}/update', [TransactionController::class, "update"])->name('transaction.update');
    Route::delete('/{transaction_id}/delete', [TransactionController::class, "delete"])->name('transaction.delete');
    Route::post('/export', [TransactionController::class, "export"])->name('report.transaction.export');
});

?>
