<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DateCalculatorController;

Route::get('/', [DateCalculatorController::class, 'index'])->name('calculator.index');
Route::post('/calculate', [DateCalculatorController::class, 'calculate'])->name('calculator.calculate');
Route::post('/add-to-date', [DateCalculatorController::class, 'addToDate'])->name('calculator.add');
Route::delete('/history', [DateCalculatorController::class, 'clearHistory'])->name('calculator.clearHistory');
Route::post('/subtract-from-date', [DateCalculatorController::class, 'subtractFromDate'])->name('calculator.subtract');
Route::post('/calculate-time', [DateCalculatorController::class, 'calculateTime'])->name('calculator.time');