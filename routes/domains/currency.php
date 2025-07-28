<?php

//Currencies

use App\Http\Controllers\CurrencyController;
use Illuminate\Support\Facades\Route;

Route::prefix('currency')->group(function () {
    Route::get('/list', [CurrencyController::class, "list"])->name('currency.list');
});


?>
