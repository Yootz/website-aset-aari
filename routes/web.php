<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MonthlyLoanReportController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\QRCodeController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
Route::post('logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('employee', [EmployeeController::class, 'index'])->name('employee.index');
Route::get('employee/load-more', [EmployeeController::class, 'loadMore'])->name('employee.loadMore');
Route::get('division', [DivisionController::class, 'index'])->name('division.index');
Route::get('division/load-more', [DivisionController::class, 'loadMore'])->name('division.loadMore');
Route::get('asset', [AssetController::class, 'index'])->name('asset.index');
Route::get('asset/load-more', [AssetController::class, 'loadMore'])->name('asset.loadMore');
Route::get('asset/create', [AssetController::class, 'create'])->middleware('admin')->name('asset.create');
Route::get('asset/lookup/{a_code}', [AssetController::class, 'lookup'])->name('asset.lookup');
Route::get('asset/{asset}', [AssetController::class, 'show'])->name('asset.show');

Route::get('peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
Route::post('peminjaman', [PeminjamanController::class, 'storeWeb'])->name('peminjaman.store');
Route::get('peminjaman', [PeminjamanController::class, 'webIndex'])->name('peminjaman.index');
Route::get('peminjaman/load-more', [PeminjamanController::class, 'loadMore'])->name('peminjaman.loadMore');
Route::get('peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

Route::middleware('admin')->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('employee', EmployeeController::class)->except(['index', 'show']);
    Route::resource('division', DivisionController::class)->except(['index', 'show']);

    Route::post('asset', [AssetController::class, 'store'])->name('asset.store');
    Route::get('createqr', [QRCodeController::class, 'index'])->name('createqr.index');

    Route::get('monitoring-peminjaman', [PeminjamanController::class, 'monitoring'])->name('peminjaman.monitoring');
    Route::get('monitoring-peminjaman/load-more', [PeminjamanController::class, 'loadMore'])->name('peminjaman.monitoring.loadMore');
    Route::patch('peminjaman/{peminjaman}/status', [PeminjamanController::class, 'updateStatusWeb'])->name('peminjaman.status');
    Route::get('laporan/peminjaman/bulanan', [MonthlyLoanReportController::class, 'page'])->name('laporan.peminjaman.bulanan');
});
