<?php

//Customer

use App\Http\Controllers\PosCustomerController;
use Illuminate\Support\Facades\Route;

Route::prefix('customer')->group(function () {
    Route::get('/', [PosCustomerController::class, "index"])->name('customer.index');
    Route::post('/store', [PosCustomerController::class, "store"])->name('customer.store');
    Route::post('/all-store', [PosCustomerController::class, "allStore"])->name('customer.all.store');
    Route::post('/{customer_id}/update', [PosCustomerController::class, "update"])->name('customer.update');
    Route::delete('/{customer_id}/delete', [PosCustomerController::class, "delete"])->name('customer.delete');
    Route::get('/all', [PosCustomerController::class, "all"])->name('customer.all');
    Route::get('/all-customers', [PosCustomerController::class, "customerAll"])->name('customer.multiselect.all');
    Route::get('/{customer_id}/get', [PosCustomerController::class, "get"])->name('customer.get');
    Route::get('/{contact}/get-by-phone-number', [PosCustomerController::class, "getByNumber"])->name('customer.number.get');
    Route::get('/{customer_id}/load', [PosCustomerController::class, "loadCustomer"])->name('customer.load');
    Route::get('/{customer_id}/all-invoices', [PosCustomerController::class, "allInvoices"])->name('customer.invoice.all');
    Route::get('/{customer_id}/all-bills', [PosCustomerController::class, "allBills"])->name('customer.bill.all');
    Route::get('/{customer_id}/all-quotations', [PosCustomerController::class, "allQuotations"])->name('customer.quotation.all');
    Route::get('/{customer_id}/due', [PosCustomerController::class, "dueAmounts"])->name('customer.due');
    Route::get('/deleted-list', [PosCustomerController::class, "deletedList"])->name('customer.deleted.list');
    Route::get('/all-deleted', [PosCustomerController::class, "deletedAll"])->name('customer.deleted.all');
    Route::get('/{customer_id}/restore', [PosCustomerController::class, "restoreCustomer"])->name('customer.restore');
    Route::get('/all-multiselect-search', [PosCustomerController::class, "multiselectCustomersSearch"])->name('customer.multiselect.all.search');

    Route::post('/import', [PosCustomerController::class, "import"])->name('customer.import');
    Route::get('/download-sample-excel', [PosCustomerController::class, "downloadSampleExcel"])->name('customer.download_sample_excel');

    Route::post('{customer_id}/send-customer-outstanding-mail', [PosCustomerController::class, "sendCustomerOutstandingMail"])->name('customer.outstanding_mail.send');
});

?>
