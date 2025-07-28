<?php

use App\Http\Controllers\PosCartController;
use App\Http\Controllers\PosOrderController;
use App\Http\Controllers\PosPaymentController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('order')->group(function () {
    Route::get('/', [PosCartController::class, "index"])->name('cart.index');
    Route::get('/{order_id}/{type}/process', [PosCartController::class, "process"])->name('cart.process');
    Route::post('/update', [PosOrderController::class, "update"])->name('cart.update');
    Route::post('/product-with-name-barcode', [PosCartController::class, "finishGoodByNameBarcode"])->name('product.name.all');
    Route::get('/{product_id}/select', [PosOrderController::class, "selectProduct"])->name('cart.select.product');
    Route::get('/get-order', [PosOrderController::class, "getOrderProduct"])->name('cart.get.products');
    Route::get('/clear-order', [PosOrderController::class, "clearOrder"])->name('cart.clear.order');
    Route::get('/get-subtotal-order', [PosOrderController::class, "getTotals"])->name('cart.getsubtotal.order');
    Route::get('/{product_id}/decrease-qty', [PosOrderController::class, "decreaseQty"])->name('cart.decrease.product');
    Route::get('/{product_id}/increase-qty', [PosOrderController::class, "increaseQty"])->name('cart.increase.product');
    Route::post('/{pos_order_item_id}/update-qty', [PosOrderController::class, "updateQty"])->name('cart.product.qty');
    Route::get('/{pos_order_item_id}/remove-item', [PosOrderController::class, "removeItem"])->name('cart.remove.product');
    Route::post('/change-price', [PosOrderController::class, "changeUnitPrice"])->name('cart.update.unit.price');
    Route::get('/{order_id}/remove-select-customer', [PosOrderController::class, "removeCustomerId"])->name('customer.remove');
    Route::post('/discount', [PosOrderController::class, "discount"])->name('cart.discount');
    Route::get('/{order_id}/remove-discount', [PosOrderController::class, "removeDiscount"])->name('remove.discount');
    Route::get('/hold-cart', [PosOrderController::class, "holdCart"])->name('cart.hold');
    Route::post('/payment-done', [PosOrderController::class, "paymentDone"])->name('order.done');
    Route::get('/{payment_id}/print', [PosPaymentController::class, "print"])->name('payment.print');
    Route::get('/{receipt_id}/print-receipt', [PosPaymentController::class, "printReceipt"])->name('receipt.print');
    Route::delete('/{receipt_id}/delete-receipt', [PosPaymentController::class, "deleteReceipt"])->name('receipt.delete');
    Route::get('/{payment_id}/return-print', [PosPaymentController::class, "returnPrint"])->name('return.print');
    // Route::get('/{payment_id}/return-print', [PosPaymentController::class,"returnPrint"])->name('cart.expire.check');
    Route::get('/get-product-by-code', [ProductController::class, 'getProductByCode'])->name('get.product.code');
});


?>
