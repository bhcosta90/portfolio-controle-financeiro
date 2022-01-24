<?php

use App\Http\Controllers\Web\CostController;
use Illuminate\Support\Facades\Route;

Route::get('cost', [CostController::class, 'index'])->name('cost.index');
Route::get('cost/create/normal', [CostController::class, 'create'])->name('cost.create.normal');
Route::get('cost/create/parcel', [CostController::class, 'create'])->name('cost.create.parcel');
Route::get('cost/create/recursive', [CostController::class, 'create'])->name('cost.create.recursive');

Route::post('cost/create/normal', [CostController::class, 'store'])->name('cost.store.normal');
Route::post('cost/create/parcel', [CostController::class, 'store'])->name('cost.store.parcel');
Route::post('cost/create/recursive', [CostController::class, 'store'])->name('cost.store.recursive');
