<?php

// Reports

use App\Http\Controllers\OutstandingReportController;
use App\Http\Controllers\ProductSalesReportController;
use App\Http\Controllers\ProfitAndLossReportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\TaxReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('report')->group(function () {
    Route::get('/', [ReportController::class, "index"])->name('report.index');
    // product sales
    Route::prefix('product-sales')->group(function () {
        Route::get('/', [ProductSalesReportController::class, "index"])->name('report.productSales.index');
        Route::get('/all', [ProductSalesReportController::class, "all"])->name('report.productSales.all');
        Route::post('/print', [ProductSalesReportController::class, "print"])->name('report.productSales.print');
        Route::post('/export', [ProductSalesReportController::class, "export"])->name('report.productSales.export');
    });
    // outstanding
    Route::prefix('outstanding')->group(function () {
        Route::get('/', [OutstandingReportController::class, "index"])->name('report.outstanding.index');
        Route::get('/all', [OutstandingReportController::class, "all"])->name('report.outstanding.all');
        Route::post('/print', [OutstandingReportController::class, "print"])->name('report.outstanding.print');
        Route::post('/export', [OutstandingReportController::class, "export"])->name('report.outstanding.export');
    });

    // Profit And Loss
    Route::prefix('profit-and-loss')->group(function () {
        Route::get('/', [ProfitAndLossReportController::class, "index"])->name('report.profitAndLoss.index');
        Route::get('/all', [ProfitAndLossReportController::class, "all"])->name('report.profitAndLoss.all');
        Route::post('/print', [ProfitAndLossReportController::class, "print"])->name('report.profitAndLoss.print');
        Route::post('/export', [ProfitAndLossReportController::class, "export"])->name('report.profitAndLoss.export');
    });

    // Stock Movements
    Route::prefix('stock-movements')->group(function () {
        Route::get('/', [StockMovementController::class, "index"])->name('report.stockMovement.index');
        Route::get('/all', [StockMovementController::class, "all"])->name('report.stockMovement.all');
    });

    // Tax Report
    Route::prefix('tax')->group(function () {
        Route::get('/', [TaxReportController::class, "index"])->name('report.tax.index');
        Route::get('/all', [TaxReportController::class, "all"])->name('report.tax.all');
        Route::post('/print', [TaxReportController::class, "print"])->name('report.tax.print');
        Route::post('/export', [TaxReportController::class, "export"])->name('report.tax.export');
    });
});


?>
