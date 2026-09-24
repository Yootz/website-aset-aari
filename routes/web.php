<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DivisionController;

Route::resource('employee', EmployeeController::class);
Route::resource('division', DivisionController::class);