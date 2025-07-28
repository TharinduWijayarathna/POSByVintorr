<?php

// Purchase Order

use App\Http\Controllers\PurchaseOrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('purchase-order')->group(function () {
    Route::get('/', [PurchaseOrderController::class, 'index'])->name('purchaseOrder.index');
    Route::get('/{purchase_order_id}/process', [PurchaseOrderController::class, 'process'])->name('purchaseOrder.process');
    Route::get('/all', [PurchaseOrderController::class, 'view'])->name('purchaseOrder.all.view');
    Route::get('/all-purchaseOrders', [PurchaseOrderController::class, 'all'])->name('purchaseOrder.bill.all');
    Route::post('/add', [PurchaseOrderController::class, 'addPurchaseOrderProduct'])->name('purchaseOrder.product.add');
    Route::get('/get-order', [PurchaseOrderController::class, 'getPurchaseOrderProduct'])->name('purchaseOrder.get.products');
    Route::get('/get', [PurchaseOrderController::class, 'getPurchaseOrder'])->name('purchaseOrder.get');
    Route::get('/get-subtotal-purchaseOrder', [PurchaseOrderController::class, 'getTotals'])->name('purchaseOrder.getSubtotal.order');
    Route::get('/{product_id}/get-order-item', [PurchaseOrderController::class, 'getOrderItem'])->name('purchaseOrder.product.get');
    Route::post('/update-qty', [PurchaseOrderController::class, 'updateQty'])->name('purchaseOrder.product.set');
    Route::post('/{product_id}/update-selected', [PurchaseOrderController::class, 'updatePurchaseOrderProductQty'])->name('purchaseOrder.product.qty');
    Route::get('/{product_id}/remove-item', [PurchaseOrderController::class, 'removeItem'])->name('purchaseOrder.remove.product');
    Route::post('/discount', [PurchaseOrderController::class, 'discount'])->name('purchaseOrder.discount');
    Route::get('/{purchase_order_id}/remove-discount', [PurchaseOrderController::class, 'removeDiscount'])->name('remove.purchaseOrder.discount');
    Route::get('/{supplier_id}/get-supplier', [PurchaseOrderController::class, 'storeSupplier'])->name('purchaseOrder.supplier.store');
    Route::get('/{currency_id}/get-currency', [PurchaseOrderController::class, 'storeCurrency'])->name('purchaseOrder.currency.store');
    Route::post('/store-ref', [PurchaseOrderController::class, 'storeRef'])->name('store.purchaseOrder.ref');
    Route::post('/{purchase_order_id}/edit-ref', [PurchaseOrderController::class, 'editRef'])->name('edit.purchaseOrder.ref');
    Route::post('/date', [PurchaseOrderController::class, 'storePODate'])->name('purchaseOrder.date.store');
    Route::post('/{purchase_order_id}/date', [PurchaseOrderController::class, 'editPODate'])->name('purchaseOrder.date.edit');
    Route::get('/get-po-supplier', [PurchaseOrderController::class, 'getPurchaseOrderSupplier'])->name('purchaseOrder.po.supplier');
    Route::get('/{purchase_order_id}/get-po-supplier', [PurchaseOrderController::class, 'getEditPurchaseOrderSupplier'])->name('edit.purchaseOrder.po.supplier');
    Route::post('/selected-supplier', [PurchaseOrderController::class, 'changePurchaseOrderSupplierDetails'])->name('change.purchaseOrder.supplier');
    Route::post('/{purchase_order_id}/selected-supplier', [PurchaseOrderController::class, 'editPurchaseOrderSupplier'])->name('edit.purchaseOrder.supplier');
    Route::get('/{purchase_order_id}/remove-supplier', [PurchaseOrderController::class, 'removeSupplier'])->name('purchaseOrder.supplier.remove');
    Route::post('/note', [PurchaseOrderController::class, 'storeNote'])->name('purchaseOrder.note.save');
    Route::get('/{purchase_order_id}/edit', [PurchaseOrderController::class, 'editPurchaseOrder'])->name('purchaseOrder.edit');
    Route::get('/{purchase_order_id}/load', [PurchaseOrderController::class, 'loadPurchaseOrder'])->name('purchaseOrder.load');
    Route::get('/{purchase_order_id}/get-purchaseOrder', [PurchaseOrderController::class, 'getRelatedPurchaseOrder'])->name('purchaseOrder.related.get');
    Route::get('/{purchase_order_id}/get-products', [PurchaseOrderController::class, 'getPurchaseOrderItems'])->name('purchaseOrder.get.related.products');
    Route::post('/get-purchase-order-item', [PurchaseOrderController::class, 'getPurchaseOrderItem'])->name('purchaseOrder.related.products');
    Route::post('/{order_item_id}/update-product', [PurchaseOrderController::class, 'updatePurchaseOrderProduct'])->name('purchaseOrder.product.update');
    Route::post('/{purchase_order_id}/edit-note', [PurchaseOrderController::class, 'editNote'])->name('purchaseOrder.note.edit');
    Route::post('/edit-supplier', [PurchaseOrderController::class, 'getEditPurchaseOrderSupplier'])->name('purchaseOrder.supplier.edit');
    Route::post('/edit-currency', [PurchaseOrderController::class, 'editCurrency'])->name('purchaseOrder.currency.edit');
    Route::post('/remove-edit-item', [PurchaseOrderController::class, 'removeSelectedProduct'])->name('purchaseOrder.remove.item');
    Route::post('/add-item', [PurchaseOrderController::class, 'addEditPurchaseOrderProduct'])->name('purchaseOrder.item.add');
    Route::get('/{purchase_order_id}/print', [PurchaseOrderController::class, 'print'])->name('purchaseOrder.print');
    Route::get('/{purchase_order_id}/get-confirm', [PurchaseOrderController::class, 'getForDelete'])->name('purchaseOrder.delete.confirm');
    Route::delete('/{purchase_order_id}/delete', [PurchaseOrderController::class, 'delete'])->name('purchaseOrder.delete');
    Route::get('/deleted-list', [PurchaseOrderController::class, 'deletedList'])->name('purchaseOrder.deleted.list');
    Route::get('/all-deleted', [PurchaseOrderController::class, 'deletedAll'])->name('purchaseOrder.deleted.all');
    Route::get('/{purchase_order_id}/get-purchase-order-totals', [PurchaseOrderController::class, 'getPurchaseOrderTotals'])->name('purchaseOrder.calculate.totals');
    Route::get('/{purchase_order_id}/restore', [PurchaseOrderController::class, 'restorePurchaseOrder'])->name('purchaseOrder.restore');
    Route::get('/{purchase_order_id}/convert', [PurchaseOrderController::class, 'convertToPurchaseOrder'])->name('purchaseOrder.convert');

    Route::get('/footer-parameters/{purchaseOrder?}', [PurchaseOrderController::class, 'getFooterParameters'])->name('purchaseOrder.parameters.footer.get');
    Route::get('/{id}/edit-footer-parameter', [PurchaseOrderController::class, 'editFooterParameter'])->name('get.purchaseOrder.parameter.footer.field');
    Route::post('/store-footer-parameter/{purchase_order_id?}', [PurchaseOrderController::class, 'storeFooterParameter'])->name('store.purchaseOrder.footer.parameter');
    Route::post('/footer-description', [PurchaseOrderController::class, 'setFooterDescription'])->name('set.purchaseOrder.parameter.footer.description');
    Route::post('/update-footer-parameter/{purchase_order_id?}', [PurchaseOrderController::class, 'updateFooterParameter'])->name('update.purchaseOrder.footer.parameter');
    Route::delete('/{field_id}/delete-footer-parameter', [PurchaseOrderController::class, 'deleteFooterParameter'])->name('delete.purchaseOrder.footer.parameter');

    Route::post('/store-parameter/{purchase_order_id?}', [PurchaseOrderController::class, 'storeParameter'])->name('store.purchaseOrder.parameter');
    Route::post('/update-parameter/{purchase_order_id?}', [PurchaseOrderController::class, 'updateParameter'])->name('update.purchaseOrder.parameter');
    Route::get('/parameters/{purchase_order_id?}', [PurchaseOrderController::class, 'getParameters'])->name('purchaseOrder.parameters.get');
    Route::post('/description', [PurchaseOrderController::class, 'setDescription'])->name('set.purchaseOrder.parameter.description');
    Route::get('/{id}/edit-parameter', [PurchaseOrderController::class, 'editParameter'])->name('get.purchaseOrder.parameter.field');
    Route::delete('/{field_id}/delete-parameter', [PurchaseOrderController::class, 'deleteParameter'])->name('delete.purchaseOrder.parameter');

    Route::post('/{purchase_order_id}/supplier-purchaseOrder-email', [PurchaseOrderController::class, 'sendSupplierPurchaseOrderEmail'])->name('supplier.purchaseOrder.mail.send');

    Route::get('/{purchase_order_id}/link', [PurchaseOrderController::class, 'createPurchaseOrderLink'])->name('purchaseOrder.link.get');
    Route::get('/view/{key}', [PurchaseOrderController::class, 'viewPurchaseOrderPage'])->name('purchaseOrder.link.view');
});
