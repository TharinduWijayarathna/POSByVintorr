<?php

use App\Http\Controllers\PosCartController;
use Illuminate\Support\Facades\Route;

Route::get('/{type?}/cart', [PosCartController::class, "index"])->name('cart');

?>
