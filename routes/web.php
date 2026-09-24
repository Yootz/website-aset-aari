<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\QRCodeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('employee', EmployeeController::class)->except(['show']);
Route::resource('division', DivisionController::class)->except(['show']);

Route::get('createqr', [QRCodeController::class, 'index'])->name('createqr.index');