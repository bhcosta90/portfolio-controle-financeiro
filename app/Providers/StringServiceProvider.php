<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class StringServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Str::macro('date', function ($date, $format = 'd/m/Y') {
            return (new \Carbon\Carbon($date))->format($format);
        });

        Str::macro('numberBr', function ($number, $onlyGreaterZero = false) {
            if ($onlyGreaterZero == true && $number < 0) {
                $number *= -1;
            }
            return number_format($number, 2, ',', '.');
        });

        Str::macro('numberEn', function ($number) {
            $value = str_replace('.', '', $number);
            return (float)str_replace(',', '.', $value);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
