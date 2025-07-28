<?php

use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

//Expenses
Route::prefix('expenses')->group(function () {
    Route::get('/', [ExpenseController::class, "index"])->name('expenses.index');
    Route::get('/create', [ExpenseController::class, "create"])->name('expense.create');
    Route::get('/all', [ExpenseController::class, "all"])->name('expenses.all');
    Route::get('/{expense_id}/get', [ExpenseController::class, "get"])->name('expense.get');
    Route::post('/all-store', [ExpenseController::class, "store"])->name('expense.store');
    Route::post('/{expense_id}/update', [ExpenseController::class, "update"])->name('expense.update');
    Route::get('/{expense_id}/remove-image', [ExpenseController::class, "removeImage"])->name('expense.image.remove');
    Route::delete('/{expense_id}/delete/recode', [ExpenseController::class, "delete"])->name('expense.delete');
    Route::get('/{expense_id}/load', [ExpenseController::class, "loadExpense"])->name('expense.load');
    Route::get('/{expense_id}/print', [ExpenseController::class, "print"])->name('expense.print');
    Route::get('/download-receipt', [ExpenseController::class, 'downloadReceipt'])->name('expense.downloadReceipt');

    Route::get('/category', [ExpenseController::class, "categoryIndex"])->name('expenses.category');
    Route::post('/new-category', [ExpenseController::class, "newCategory"])->name('expenses.category.store');
    Route::get('/category-all', [ExpenseController::class, "categoryAll"])->name('expenses.category.all');
    Route::get('/category-select-all', [ExpenseController::class, "categorySelectAll"])->name('expenses.category.multiselect.all');
    Route::get('/{category_id}/category-get', [ExpenseController::class, "getCategory"])->name('expenses.category.get');
    Route::post('/{category_id}/category-update', [ExpenseController::class, "updateCategory"])->name('expenses.category.update');
    Route::delete('/{category_id}/category-delete', [ExpenseController::class, "deleteCategory"])->name('expenses.category.delete');
    Route::get('/deleted-list', [ExpenseController::class, "deletedList"])->name('expenses.deleted.list');
    Route::get('/all-deleted', [ExpenseController::class, "deletedAll"])->name('expenses.deleted.all');
    Route::get('/{expense_id}/restore', [ExpenseController::class, "restoreExpense"])->name('expenses.restore');
    Route::delete('/{expense_id}/category/delete', [ExpenseController::class, "deleteSupplierExpense"])->name('expenses.supplier.delete');

    Route::post('/{expense_id}/expense-mail-send', [ExpenseController::class, "sendSupplierExpenseMail"])->name('expenses.mail.send');

    Route::post('/export', [ExpenseController::class, "export"])->name('report.expense.export');
});

?>
