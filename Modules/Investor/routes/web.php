<?php

use Illuminate\Support\Facades\Route;
use Modules\Investor\Http\Controllers\InvestorController;

Route::prefix('investor-relations')->name('investor.')->group(function () {
    Route::get('/', [InvestorController::class, 'index'])->name('index');
    Route::get('/financial-reports', [InvestorController::class, 'financialReports'])->name('financial-reports');
    Route::get('/press-releases', [InvestorController::class, 'pressReleases'])->name('press-releases.index');
    Route::get('/press-releases/{slug}', [InvestorController::class, 'showPressRelease'])->name('press-releases.show');
    Route::get('/stock-data', [InvestorController::class, 'stockData'])->name('stock-data');
    Route::get('/shareholder-meetings', [InvestorController::class, 'shareholderMeetings'])->name('shareholder-meetings');
});
