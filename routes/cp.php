<?php

use DoubleThreeDigital\SimpleCommerce\Http\Controllers\CP\ReportingController;
use DoubleThreeDigital\SimpleCommerce\Http\Controllers\CP\SalesReportController;
use DoubleThreeDigital\SimpleCommerce\Http\Controllers\CP\VariantFieldtypeController;
use Illuminate\Support\Facades\Route;

Route::prefix('simple-commerce')->name('simple-commerce.')->group(function () {
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [SalesReportController::class, 'index'])->name('sales');
    });

    Route::prefix('fieldtype-api')->name('fieldtype-api.')->group(function () {
        Route::post('product-variant', [VariantFieldtypeController::class, '__invoke'])->name('product-variant');
    });
});
