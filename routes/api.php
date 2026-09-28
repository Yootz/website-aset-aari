<?php

use App\Http\Controllers\MonthlyLoanReportController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

Route::get('/laporan/peminjaman/bulanan', MonthlyLoanReportController::class)
    ->name('api.laporan.peminjaman.bulanan');

Route::prefix('peminjaman')->name('api.peminjaman.')->group(function () {
    Route::get('/', [PeminjamanController::class, 'index'])->name('index');
    Route::post('/', [PeminjamanController::class, 'store'])->name('store');
    Route::patch('/{peminjaman}/status', [PeminjamanController::class, 'updateStatus'])->name('status');
    Route::post('/{peminjaman}/return', [PeminjamanController::class, 'returnAsset'])->name('return');
});
