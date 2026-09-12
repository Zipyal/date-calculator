<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DateCalculatorController;

Route::get('/', [DateCalculatorController::class, 'index'])->name('calculator.index');
Route::post('/calculate', [DateCalculatorController::class, 'calculate'])->name('calculator.calculate');
