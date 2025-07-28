<?php

//Quotations

use App\Http\Controllers\PublicViewQuotationController;
use App\Http\Controllers\QuotationController;
use Illuminate\Support\Facades\Route;

Route::prefix('quotation')->group(function () {
    Route::get('/', [QuotationController::class, "index"])->name('quotation.index');
    Route::get('/{quotation_id}/process', [QuotationController::class, "process"])->name('quotation.process');
    Route::get('/all', [QuotationController::class, "viewAllInvoices"])->name('quotation.all.view');
    Route::get('/all-quotations', [QuotationController::class, "all"])->name('quotation.bill.all');
    Route::post('/add', [QuotationController::class, "addQuotationProduct"])->name('quotation.product.add');
    Route::get('/get-order', [QuotationController::class, "getQuotationProduct"])->name('quotation.get.products');
    Route::get('/get', [QuotationController::class, "getInvoice"])->name('quotation.get');
    Route::get('/get-subtotal-quotation', [QuotationController::class, "getTotals"])->name('quotation.getSubtotal.order');
    Route::get('/{product_id}/get-order-item', [QuotationController::class, "getOrderItem"])->name('quotation.product.get');
    Route::post('/update-qty', [QuotationController::class, "updateQty"])->name('quotation.product.set');
    Route::post('/{product_id}/update-selected', [QuotationController::class, "updateQuotationProductQty"])->name('quotation.product.qty');
    Route::get('/{product_id}/remove-item', [QuotationController::class, "removeItem"])->name('quotation.remove.product');
    Route::post('/discount', [QuotationController::class, "discount"])->name('quotation.discount');
    Route::get('/{quotation_id}/remove-discount', [QuotationController::class, "removeDiscount"])->name('remove.quotation.discount');
    Route::get('/{customer_id}/get-customer', [QuotationController::class, "storeCustomer"])->name('quotation.customer.store');
    Route::get('/{currency_id}/get-currency', [QuotationController::class, "storeCurrency"])->name('quotation.currency.store');
    Route::post('/store-ref', [QuotationController::class, "storeRef"])->name('store.quotation.ref');
    Route::post('/{quotation_id}/edit-ref', [QuotationController::class, "editRef"])->name('edit.quotation.ref');
    Route::get('/get-loyalty-customer', [QuotationController::class, "getLoyaltyCustomer"])->name('quotation.loyalty.customers');
    Route::get('/{quotation_id}/get-loyalty-customer', [QuotationController::class, "getEditLoyaltyCustomer"])->name('edit.quotation.loyalty.customers');
    Route::post('/selected-customer', [QuotationController::class, "changeQuotationCustomerDetails"])->name('change.quotation.customer');
    Route::post('/{quotation_id}/selected-customer', [QuotationController::class, "editQuotationCustomer"])->name('edit.quotation.customer');
    Route::get('/{quotation_id}/remove-customer', [QuotationController::class, "removeCustomer"])->name('quotation.customer.remove');
    Route::post('/note', [QuotationController::class, "storeNote"])->name('quotation.note.save');
    Route::get('/{quotation_id}/edit', [QuotationController::class, "editInvoice"])->name('quotation.edit');
    Route::get('/{quotation_id}/load', [QuotationController::class, "loadQuotation"])->name('quotation.load');
    Route::get('/{quotation_id}/get-quotation', [QuotationController::class, "getRelatedQuotation"])->name('quotation.related.get');
    Route::get('/{quotation_id}/get-products', [QuotationController::class, "getQuotationItems"])->name('quotation.get.related.products');
    Route::post('/get-invoice-item', [QuotationController::class, "getQuotationItem"])->name('quotation.related.products');
    Route::post('/{order_item_id}/update-product', [QuotationController::class, "updateQuotationProduct"])->name('quotation.product.update');
    Route::post('/{quotation_id}/edit-note', [QuotationController::class, "editNote"])->name('quotation.note.edit');
    Route::post('/edit-customer', [QuotationController::class, "editCustomer"])->name('quotation.customer.edit');
    Route::post('/edit-currency', [QuotationController::class, "editCurrency"])->name('quotation.currency.edit');
    Route::post('/remove-edit-item', [QuotationController::class, "removeSelectedProduct"])->name('quotation.remove.item');
    Route::post('/add-item', [QuotationController::class, "addEditInvoiceProduct"])->name('quotation.item.add');
    Route::get('/{quotation_id}/print', [QuotationController::class, "print"])->name('quotation.print');
    Route::get('/{quotation_id}/get-confirm', [QuotationController::class, "getForDelete"])->name('quotation.delete.confirm');
    Route::delete('/{quotation_id}/delete', [QuotationController::class, "delete"])->name('quotation.delete');
    Route::get('/deleted-list', [QuotationController::class, "deletedList"])->name('quotation.deleted.list');
    Route::get('/all-deleted', [QuotationController::class, "deletedAll"])->name('quotation.deleted.all');
    Route::get('/{quotation_id}/get-invoice-totals', [QuotationController::class, "getInvoiceTotals"])->name('quotation.calculate.totals');
    Route::get('/{quotation_id}/restore', [QuotationController::class, "restoreQuotation"])->name('quotation.restore');
    Route::get('/{quotation_id}/convert', [QuotationController::class, "convertToInvoice"])->name('quotation.convert');

    Route::get('/footer-parameters/{quotation?}', [QuotationController::class, "getFooterParameters"])->name('quotation.parameters.footer.get');
    Route::get('/{id}/edit-footer-parameter', [QuotationController::class, "editFooterParameter"])->name('get.quotation.parameter.footer.field');
    Route::post('/store-footer-parameter/{quotation_id?}', [QuotationController::class, "storeFooterParameter"])->name('store.quotation.footer.parameter');
    Route::post('/footer-description', [QuotationController::class, "setFooterDescription"])->name('set.quotation.parameter.footer.description');
    Route::post('/update-footer-parameter/{quotation_id?}', [QuotationController::class, "updateFooterParameter"])->name('update.quotation.footer.parameter');
    Route::delete('/{field_id}/delete-footer-parameter', [QuotationController::class, "deleteFooterParameter"])->name('delete.quotation.footer.parameter');

    Route::post('/store-parameter/{quotation_id?}', [QuotationController::class, "storeParameter"])->name('store.quotation.parameter');
    Route::post('/update-parameter/{quotation_id?}', [QuotationController::class, "updateParameter"])->name('update.quotation.parameter');
    Route::get('/parameters/{quotation_id?}', [QuotationController::class, "getParameters"])->name('quotation.parameters.get');
    Route::post('/description', [QuotationController::class, "setDescription"])->name('set.quotation.parameter.description');
    Route::get('/{id}/edit-parameter', [QuotationController::class, "editParameter"])->name('get.quotation.parameter.field');
    Route::delete('/{field_id}/delete-parameter', [QuotationController::class, "deleteParameter"])->name('delete.quotation.parameter');

    Route::post('/{quotation_id}/customer-quotation-email', [QuotationController::class, "sendCustomerQuotationEmail"])->name('customer.quotation.mail.send');

    Route::get('/{quotation_id}/link', [QuotationController::class, "createQuotationLink"])->name('quotation.link.get');
    Route::get('/view/{key}', [PublicViewQuotationController::class, "viewQuotationPage"])->name('quotation.link.view');
});

?>
