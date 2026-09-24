<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\QRCodeController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('employee', EmployeeController::class);
Route::resource('division', DivisionController::class);

Route::get('createqr', [QRCodeController::class, 'index'])->name('createqr.index');