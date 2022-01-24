<?php

use App\Http\Controllers\Web\IncomeController;
use Illuminate\Support\Facades\Route;

Route::get('income', [IncomeController::class, 'index'])->name('income.index');
Route::get('income/create/normal', [IncomeController::class, 'create'])->name('income.create.normal');
Route::get('income/create/parcel', [IncomeController::class, 'create'])->name('income.create.parcel');
Route::get('income/create/recursive', [IncomeController::class, 'create'])->name('income.create.recursive');

Route::post('income/create/normal', [IncomeController::class, 'store'])->name('income.store.normal');
Route::post('income/create/parcel', [IncomeController::class, 'store'])->name('income.store.parcel');
Route::post('income/create/recursive', [IncomeController::class, 'store'])->name('income.store.recursive');
