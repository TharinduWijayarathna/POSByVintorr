<?php

//Invoices

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PublicViewInvoiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('invoice')->group(function () {
    Route::get('/', [InvoiceController::class, "index"])->name('invoice.index');
    Route::get('/{invoice_id}/process', [InvoiceController::class, "process"])->name('invoice.process');
    Route::get('/all', [InvoiceController::class, "viewAllInvoices"])->name('invoice.all.view');
    Route::get('/all-invoices', [InvoiceController::class, "all"])->name('invoice.bill.all');
    Route::get('/{invoice_id}/get', [InvoiceController::class, "getInvoice"])->name('invoice.get');
    Route::get('/{invoice_id}/get-invoice', [InvoiceController::class, "getRelatedInvoice"])->name('invoice.related.get');
    Route::get('/{customer_id}/get-customer', [InvoiceController::class, "storeCustomer"])->name('invoice.customer.store');
    Route::get('/{currency_id}/get-currency', [InvoiceController::class, "storeCurrency"])->name('invoice.currency.store');
    Route::post('/invoice-ref', [InvoiceController::class, "storeInvoiceRef"])->name('store.invoice.ref');
    Route::post('/{invoice_id}/invoice-ref', [InvoiceController::class, "editInvoiceRef"])->name('edit.invoice.ref');
    Route::post('/date', [InvoiceController::class, "storeDate"])->name('invoice.date.store');
    Route::post('/dueDate', [InvoiceController::class, "storeDueDate"])->name('invoice.dueDate.store');
    Route::post('/note', [InvoiceController::class, "storeNote"])->name('invoice.note.save');
    Route::post('/code/{invoice_id?}', [InvoiceController::class, "storeCode"])->name('invoice.code.save');
    Route::post('/{invoice_id}/date', [InvoiceController::class, "editDate"])->name('invoice.date.edit');
    Route::post('/{invoice_id}/dueDate', [InvoiceController::class, "editDueDate"])->name('invoice.dueDate.edit');
    Route::post('/{invoice_id}/edit-note', [InvoiceController::class, "editNote"])->name('invoice.note.edit');
    Route::post('/edit-customer', [InvoiceController::class, "editCustomer"])->name('invoice.customer.edit');
    Route::post('/edit-currency', [InvoiceController::class, "editCurrency"])->name('invoice.currency.edit');
    Route::post('/update-qty', [InvoiceController::class, "updateQty"])->name('invoice.product.set');
    Route::post('/add', [InvoiceController::class, "addInvoiceProduct"])->name('invoice.product.add');
    Route::post('/add-item', [InvoiceController::class, "addEditInvoiceProduct"])->name('invoice.item.add');
    Route::get('/get-order', [InvoiceController::class, "getOrderProduct"])->name('invoice.get.products');
    Route::get('/{invoice_id}/get-products', [InvoiceController::class, "getInvoiceProduct"])->name('invoice.get.related.products');
    Route::get('/{product_id}/get-order-item', [InvoiceController::class, "getOrderItem"])->name('invoice.product.get');
    Route::post('/get-invoice-item', [InvoiceController::class, "getInvoiceItem"])->name('invoice.related.products');
    Route::post('/{product_id}/update-selected', [InvoiceController::class, "updateInvoiceProductQty"])->name('invoice.product.qty');
    Route::post('/{order_item_id}/update-product', [InvoiceController::class, "updateInvoiceProduct"])->name('invoice.product.update');
    Route::delete('/{product_id}/remove-item', [InvoiceController::class, "removeItem"])->name('invoice.remove.product');
    Route::post('/remove-edit-item', [InvoiceController::class, "removeSelectedProduct"])->name('invoice.remove.item');
    Route::get('/get-subtotal-order', [InvoiceController::class, "getTotals"])->name('invoice.getSubtotal.order');
    Route::get('/{invoice_id}/get-newTotal-order', [InvoiceController::class, "getNewTotals"])->name('invoice.getNewSubtotal.order');
    Route::get('/{invoice_id}/get-invoice-totals', [InvoiceController::class, "getInvoiceTotals"])->name('invoice.calculate.totals');
    Route::get('/{invoice_id}/check-status', [InvoiceController::class, "checkInvoiceStatus"])->name('invoice.check.status');
    Route::get('/get-loyalty-customer', [InvoiceController::class, "getLoyaltyCustomer"])->name('invoice.loyalty.customers');
    Route::get('/{invoice_id}/get-invoice-customer', [InvoiceController::class, "getInvoiceCustomer"])->name('get.invoice.customer');
    Route::post('/invoice-customer', [InvoiceController::class, "changeInvoiceCustomer"])->name('change.invoice.customer');
    Route::post('/{invoice_id}/invoice-customer-edit', [InvoiceController::class, "editInvoiceCustomer"])->name('edit.invoice.customer');
    Route::get('/hold-cart', [InvoiceController::class, "holdCart"])->name('invoice.hold');
    Route::get('/{invoice_id}/edit', [InvoiceController::class, "editInvoice"])->name('invoice.edit');
    Route::get('/{invoice_id}/load', [InvoiceController::class, "loadInvoice"])->name('invoice.load');
    Route::get('/{bill_id}/edit-bill', [InvoiceController::class, "editBill"])->name('invoice.bill.edit');
    Route::get('/{invoice_id}/get-confirm', [InvoiceController::class, "getForDelete"])->name('invoice.delete.confirm');
    Route::delete('/{invoice_id}/delete', [InvoiceController::class, "delete"])->name('invoice.delete');
    Route::get('/deleted-list', [InvoiceController::class, "deletedList"])->name('invoice.deleted.list');
    Route::get('/all-deleted', [InvoiceController::class, "deletedAll"])->name('invoice.deleted.all');
    Route::get('/{invoice_id}/restore', [InvoiceController::class, "restoreInvoice"])->name('invoice.restore');

    Route::post('/store-parameter/{invoice_id?}', [InvoiceController::class, "storeParameter"])->name('store.invoice.parameter');
    Route::post('/{invoice_id}/update-parameter', [InvoiceController::class, "updateParameter"])->name('update.invoice.parameter');
    Route::get('/parameters/{invoice_id?}', [InvoiceController::class, "getParameters"])->name('invoice.parameters.get');
    Route::post('/description', [InvoiceController::class, "setDescription"])->name('set.parameter.description');
    Route::get('/{id}/edit-parameter', [InvoiceController::class, "editParameter"])->name('get.parameter.field');
    Route::delete('/{field_id}/delete-parameter', [InvoiceController::class, "deleteParameter"])->name('delete.parameter');

    Route::post('/store-footer-parameter/{invoice_id?}', [InvoiceController::class, "storeFooterParameter"])->name('store.invoice.footer.parameter');
    Route::post('/update-footer-parameter/{invoice_id?}', [InvoiceController::class, "updateFooterParameter"])->name('update.invoice.footer.parameter');
    Route::get('/footer-parameters/{invoice_id?}', [InvoiceController::class, "getFooterParameters"])->name('invoice.parameters.footer.get');
    Route::post('/footer-description', [InvoiceController::class, "setFooterDescription"])->name('set.parameter.footer.description');
    Route::get('/{id}/edit-footer-parameter', [InvoiceController::class, "editFooterParameter"])->name('get.parameter.footer.field');
    Route::delete('/{field_id}/delete-footer-parameter', [InvoiceController::class, "deleteFooterParameter"])->name('delete.footer.parameter');

    Route::post('/{invoice_id}/customer-invoice-email', [InvoiceController::class, "sendCustomerInvoiceEmail"])->name('customer.invoice.mail.send');

    Route::get('/{invoice_id}/link', [InvoiceController::class, "createInvoiceLink"])->name('invoice.link.get');

    Route::post('/export', [InvoiceController::class, "export"])->name('report.invoice.export');
});

?>
