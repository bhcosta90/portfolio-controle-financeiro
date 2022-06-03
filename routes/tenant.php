<?php

declare(strict_types=1);

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
    Auth::routes();

    Route::get('/', function () {
        return redirect('/home');
        // return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });
    Route::as('admin.')->middleware('auth')->group(function(){
        Route::view('/home', 'admin.home')->name('home');
        Route::prefix('admin')->group(fn() => include __DIR__ . '/tenant_web.php');
    });
});

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api')->as('api.')->group(function () {
    include __DIR__ . '/tenant_api.php';
});
