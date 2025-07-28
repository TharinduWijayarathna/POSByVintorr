<?php
//Products

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, "index"])->name('products.index');
    Route::get('/all', [ProductController::class, "all"])->name('products.all');
    Route::get('/{product_id}/get', [ProductController::class, "get"])->name('product.get');
    Route::get('/{pos_order_item_id}/get-order-item', [ProductController::class, "getOrderItem"])->name('order.product.get');
    Route::get('/{product_id}/get-details', [ProductController::class, "getWithDetails"])->name('product.get.details');
    Route::post('/search/product', [ProductController::class, 'search'])->name('product.search');
    Route::get('/all-cart-products', [ProductController::class, "cartAll"])->name('cart.products.all');
    Route::get('/limited/products', [ProductController::class, "getLimitedProducts"])->name('limited.products');
    Route::post('/all-store', [ProductController::class, "store"])->name('product.store');
    Route::post('/{product_id}/update', [ProductController::class, "update"])->name('product.update');
    Route::get('/{product_id}/remove', [ProductController::class, "productImageRemove"])->name('product.image.remove');
    Route::post('/{product_id}/stock-update', [ProductController::class, "stockUpdate"])->name('stock.update');
    Route::delete('/{customer_id}/delete', [ProductController::class, "delete"])->name('product.delete');
    Route::get('/list', [ProductController::class, "list"])->name('products.list');
    Route::get('/stockable/list', [ProductController::class, "stockableList"])->name('stockable.products.list');
    Route::get('/stockable/multiselect', [ProductController::class, "stockableListMultiselect"])->name('stockable.products.multiselect');

    Route::get('/rol', [ProductController::class, "rolProducts"])->name('products.rol');
    Route::get('/{id}/all-rol', [ProductController::class, "allRolProducts"])->name('products.rol.all');
    Route::get('/category-store', [ProductController::class, "storeCategory"])->name('products.category');
    Route::get('/{category_id}/category/all', [ProductController::class, "searchByCategory"])->name('products.category.all');
    Route::get('/count', [ProductController::class, "getCount"])->name('product.count.get');
    Route::get('/deleted-list', [ProductController::class, "deletedList"])->name('product.deleted.list');
    Route::get('/all-deleted', [ProductController::class, "deletedAll"])->name('product.deleted.all');
    Route::get('/{product_id}/restore', [ProductController::class, "restoreDeletedProduct"])->name('product.restore');

    Route::post('/import', [ProductController::class, "import"])->name('products.import');
    Route::get('/download-sample-excel', [ProductController::class, "downloadSampleExcel"])->name('products.download_sample_excel');
    Route::get('/get-saved-product', [ProductController::class, "getSavedProduct"])->name('products.saved.get');

    Route::post('barcode', [ProductController::class, "barcodePrint"])->name('products.barcode.print');
    Route::get('/all/multiselect/search', [ProductController::class, "multiselectProductSearch"])->name('product.multiselect.all.search');
});

?>
