<?php

declare(strict_types=1);

use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\RecurrenceController;
use App\Http\Controllers\Api\Relationship\{CustomerController, SupplierController};
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });
});

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api')->group(function () {
    Route::prefix('relationship')->group(function () {
        Route::resource('customer', CustomerController::class);
        Route::resource('supplier', SupplierController::class);
    });
    Route::resource('recurrence', RecurrenceController::class);
    Route::resource('bank', BankController::class);

    Route::prefix('charge')->group(function () {
        Route::resource('receive', RecurrenceController::class);
    });
});
