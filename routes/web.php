<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\QRCodeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('employee', EmployeeController::class)->except(['show']);
Route::resource('division', DivisionController::class)->except(['show']);

Route::get('createqr', [QRCodeController::class, 'index'])->name('createqr.index');
Route::get('peminjaman', [PeminjamanController::class, 'webIndex'])->name('peminjaman.index');
Route::get('peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
Route::post('peminjaman', [PeminjamanController::class, 'storeWeb'])->name('peminjaman.store');
Route::get('monitoring-peminjaman', [PeminjamanController::class, 'monitoring'])->name('peminjaman.monitoring');
Route::patch('peminjaman/{peminjaman}/status', [PeminjamanController::class, 'updateStatusWeb'])->name('peminjaman.status');
Route::get('peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
Route::get('asset', [AssetController::class, 'index'])->name('asset.index');
Route::get('asset/{asset}', [AssetController::class, 'show'])->name('asset.show');
Route::get('aset', [AssetController::class, 'lookup'])->name('asset.lookup');
