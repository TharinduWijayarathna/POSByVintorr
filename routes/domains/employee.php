<?php

// Employee

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::prefix('employee')->group(function () {
    Route::get('/', [EmployeeController::class, 'index'])->name('employee.index');
    Route::post('/all-store', [EmployeeController::class, 'allStore'])->name('employee.all.store');
    Route::post('/{employee_id}/update', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('/{employee_id}/delete', [EmployeeController::class, 'delete'])->name('employee.delete');
    Route::get('/all', [EmployeeController::class, 'all'])->name('employee.all');
    Route::get('/all-multiselect', [EmployeeController::class, 'multiselectEmployees'])->name('employee.multiselect.all');
    Route::get('/all-multiselect-search', [EmployeeController::class, 'multiselectEmployeesSearch'])->name('employee.multiselect.all.search');
    Route::get('/{employee_id}/get', [EmployeeController::class, 'get'])->name('employee.get');
    Route::get('/{employee_id}/load', [EmployeeController::class, 'loadEmployee'])->name('employee.load');
    Route::get('/{employee_id}/all-expenses', [EmployeeController::class, 'allExpenses'])->name('employee.expenses.all');
    Route::get('/deleted-list', [EmployeeController::class, 'deletedList'])->name('employee.deleted.list');
    Route::get('/all-deleted', [EmployeeController::class, 'deletedAll'])->name('employee.deleted.all');
    Route::get('/{employee_id}/restore', [EmployeeController::class, 'restoreEmployee'])->name('employee.restore');

    Route::get('/{employee_id}/all-payrolls', [EmployeeController::class, 'allPayrolls'])->name('employee.payrolls.all');

    Route::post('/import', [EmployeeController::class, 'import'])->name('employee.import');
    Route::get('/download-sample-excel', [EmployeeController::class, 'downloadSampleExcel'])->name('employee.download_sample_excel');
});
