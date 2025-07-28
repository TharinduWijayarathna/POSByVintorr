<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('dashboard');

Route::prefix('dashboard')->group(function () {
    Route::post('/targets', [HomeController::class, 'targetStore'])->name('dashboard.targets.store');
    Route::post('/get-sales', [HomeController::class, 'getSales'])->name('dashboard.sales.get');
    Route::post('/get-incomes', [HomeController::class, 'getIncomes'])->name('dashboard.incomes.get');
    Route::post('/get-expenses', [HomeController::class, 'getExpenses'])->name('dashboard.expenses.get');
    Route::post('/getExpenseChartData', [HomeController::class, 'ExpensesChartData'])->name('dashboard.expensesChartData.get');
    Route::get('/get-transaction-balance/{currency_id}', [HomeController::class, 'getTransactionBalance'])->name('dashboard.transactionBalance.get');
    Route::get('/get-dashboard-controller', [HomeController::class, 'getDashboardController'])->name('dashboard.controller.get');
    Route::post('/update-dashboard-controller', [HomeController::class, 'updateDashboardController'])->name('dashboard.controller.update');
    Route::get('/get-total_tax-amount/{currency_id}', [HomeController::class, 'getTotalTaxAmount'])->name('dashboard.totalTax.get');
});
