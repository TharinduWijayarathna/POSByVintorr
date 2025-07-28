<?php
// Payroll

use App\Http\Controllers\PayrollController;
use Illuminate\Support\Facades\Route;

Route::prefix('payrolls')->group(function () {
    Route::get('/', [PayrollController::class, "index"])->name('payrolls.index');
    Route::get('/create', [PayrollController::class, "create"])->name('payroll.create');
    Route::get('/all', [PayrollController::class, "all"])->name('payrolls.all');
    Route::get('/{payroll_id}/get', [PayrollController::class, "get"])->name('payroll.get');
    Route::post('/all-store', [PayrollController::class, "store"])->name('payroll.store');
    Route::post('/{payroll_id}/update', [PayrollController::class, "update"])->name('payroll.update');
    Route::get('/{payroll_id}/remove-image', [PayrollController::class, "removeImage"])->name('payrolls.image.remove');
    Route::delete('/{payroll_id}/delete/recode', [PayrollController::class, "delete"])->name('payroll.delete');
    Route::get('/{payroll_id}/load', [PayrollController::class, "loadPayroll"])->name('payroll.load');
    Route::get('/{payroll_id}/print', [PayrollController::class, "print"])->name('payroll.print');
    Route::get('/download-receipt', [PayrollController::class, 'downloadReceipt'])->name('payrolls.downloadReceipt');

    Route::get('/deleted-list', [PayrollController::class, "deletedList"])->name('payrolls.deleted.list');
    Route::get('/all-deleted', [PayrollController::class, "deletedAll"])->name('payrolls.deleted.all');
    Route::get('/{payroll_id}/restore', [PayrollController::class, "restorePayroll"])->name('payrolls.restore');
    Route::delete('/{payroll_id}/category/delete', [PayrollController::class, "deleteEmployeePayroll"])->name('payrolls.employee.delete');

    Route::post('/{payroll_id}/payroll-mail-send', [PayrollController::class, "sendEmployeePayrollMail"])->name('payrolls.mail.send');
    Route::post('/export', [PayrollController::class, "export"])->name('report.payroll.export');
});
?>
