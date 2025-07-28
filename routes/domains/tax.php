<?php
//Taxes

use App\Http\Controllers\TaxController;
use Illuminate\Support\Facades\Route;

Route::prefix('taxes')->group(function () {
    Route::get('/all', [TaxController::class, "all"])->name('tax.all');
    Route::get('/list', [TaxController::class, "list"])->name('tax.list');
    Route::post('/store', [TaxController::class, "store"])->name('tax.store');
    Route::delete('/{tax_id}/delete', [TaxController::class, "delete"])->name('tax.delete');
    Route::get('/{tax_id}/get', [TaxController::class, "get"])->name('tax.get');
    Route::get('/get/latest', [TaxController::class, "getLatestTax"])->name('tax.latest.get');
    Route::post('/{tax_id}/update', [TaxController::class, "update"])->name('tax.update');

    Route::post('{product_id}/tax', [TaxController::class, "storeProductTax"])->name('product.taxes.store');
    Route::get('/{product_id}/get-product-tax', [TaxController::class, "getProductTax"])->name('product.taxes.get');
    Route::post('{product_id}/tax/delete', [TaxController::class, "deleteProductTax"])->name('product.taxes.delete');
});
?>
