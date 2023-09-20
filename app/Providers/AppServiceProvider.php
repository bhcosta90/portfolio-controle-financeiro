<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //return intval($valor * ($p = pow(10, $precisao))) / $p;

        Str::macro('truncate', fn(mixed $value, $precision = 2) => intval($value * ($p = pow(10, $precision))) / $p);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
