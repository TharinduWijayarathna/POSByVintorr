<?php

//Supplier

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::prefix('supplier')->group(function () {
    Route::get('/', [SupplierController::class, "index"])->name('supplier.index');
    Route::post('/all-store', [SupplierController::class, "allStore"])->name('supplier.all.store');
    Route::post('/{supplier_id}/update', [SupplierController::class, "update"])->name('supplier.update');
    Route::delete('/{supplier_id}/delete', [SupplierController::class, "delete"])->name('supplier.delete');
    Route::get('/all', [SupplierController::class, "all"])->name('supplier.all');
    Route::get('/all-multiselect', [SupplierController::class, "multiselectSuppliers"])->name('supplier.multiselect.all');
    Route::get('/all-multiselect-search', [SupplierController::class, "multiselectSuppliersSearch"])->name('supplier.multiselect.all.search');
    Route::get('/{supplier_id}/get', [SupplierController::class, "get"])->name('supplier.get');
    Route::get('/{supplier_id}/load', [SupplierController::class, "loadSupplier"])->name('supplier.load');
    Route::get('/{supplier_id}/all-expenses', [SupplierController::class, "allExpenses"])->name('supplier.expenses.all');
    Route::get('/{supplier_id}/expense/get', [SupplierController::class, "getExpenseSupplier"])->name('supplier.expense.get');
    Route::get('/deleted-list', [SupplierController::class, "deletedList"])->name('supplier.deleted.list');
    Route::get('/all-deleted', [SupplierController::class, "deletedAll"])->name('supplier.deleted.all');
    Route::get('/{supplier_id}/restore', [SupplierController::class, "restoreSupplier"])->name('supplier.restore');
    Route::get('/{supplier_id}/all-purchase-order', [SupplierController::class, "allPurchaseOrder"])->name('supplier.purchaseOrder.all');
    Route::get('/{supplier_id}/payroll/get', [SupplierController::class, "getPayrollSupplier"])->name('supplier.payroll.get');

    Route::post('/import', [SupplierController::class, "import"])->name('supplier.import');
    Route::get('/download-sample-excel', [SupplierController::class, "downloadSampleExcel"])->name('supplier.download_sample_excel');
});

?>
