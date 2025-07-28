<?php


//Credit Invoice

use App\Http\Controllers\CreditInvoiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('credit-invoice')->group(function () {
    Route::get('/', [CreditInvoiceController::class, "index"])->name('credit.index');
});
