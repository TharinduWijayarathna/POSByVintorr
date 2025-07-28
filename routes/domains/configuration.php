<?php

//Configuration

use App\Http\Controllers\ConfigurationController;
use Illuminate\Support\Facades\Route;

Route::prefix('configuration')->group(function () {
    Route::get('/', [ConfigurationController::class, "index"])->name('configuration.index');
    Route::post('/store', [ConfigurationController::class, "store"])->name('configuration.store');
    Route::post('/{id}/update', [ConfigurationController::class, "update"])->name('configuration.update');
    Route::delete('/{id}/delete', [ConfigurationController::class, "delete"])->name('configuration.delete');
    Route::get('/{id}/remove-logo', [ConfigurationController::class, "removeLogo"])->name('configuration.logo.remove');
    Route::get('/{id}/remove-bill-logo', [ConfigurationController::class, "removeBillLogo"])->name('configuration.bill.logo.remove');
    Route::get('/{id}/remove-invoice-logo', [ConfigurationController::class, "removeInvoiceLogo"])->name('configuration.invoice.logo.remove');
    Route::get('/{id}/remove-banner', [ConfigurationController::class, "removeBanner"])->name('configuration.banner.remove');
    Route::get('/get', [ConfigurationController::class, "get"])->name('configuration.get');
    Route::get('/{status}/change', [ConfigurationController::class, "change"])->name('configuration.site.status');
    Route::get('/{val}/change-switch', [ConfigurationController::class, "changeImportSwitch"])->name('configuration.change.switch');
    Route::post('/{val}/monthly-outstanding-report/store', [ConfigurationController::class, "storeDateValue"])->name('configuration.monthly_outstanding_report.store');
    Route::post('/{val}/monthly-business-summary-report/store', [ConfigurationController::class, "storeBusinessSummaryEmailDateValue"])->name('configuration.monthly_business_summary_report.store');
    Route::get('/monthly-outstanding-report/get', [ConfigurationController::class, "getDateValue"])->name('configuration.monthly_outstanding_report.get');
    Route::get('/monthly-business-summary-report/get', [ConfigurationController::class, "getMonthlyBusinessEmailDateValue"])->name('configuration.monthly_business_summary_report.get');

    Route::get('/monthly-customer-outstanding-report/send', [ConfigurationController::class, "sendMonthlyCustomerOutstandingReport"])->name('configuration.monthly_outstanding_report.send');
    Route::get('/monthly-business-summary-report/send', [ConfigurationController::class, "sendMonthlyBusinessReport"])->name('configuration.monthly_business_summary_report.send');

    Route::get('/reset-token-email/send', [ConfigurationController::class, "sendTokenEmail"])->name('configuration.send_token_email.send');
    Route::post('/reset-account/{token}', [ConfigurationController::class, "resetAccount"])->name('configuration.reset.account');
});
