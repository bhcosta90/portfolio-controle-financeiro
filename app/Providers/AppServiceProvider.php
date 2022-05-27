<?php

namespace App\Providers;

use App\Repositories\Contracts\RelationshipContract;
use App\Repositories\Transactions\DatabaseTransaction;
use Costa\Shareds\Contracts\TransactionContract;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\{Str, ServiceProvider};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {       
        Paginator::useBootstrap();
        
        Str::macro('date', function ($date, $format = 'd/m/Y') {
            return (new \Carbon\Carbon($date))->format($format);
        });

        Str::macro('numberEnToBr', function ($number, $onlyGreaterZero = false) {
            if ($onlyGreaterZero == true && $number < 0) {
                $number *= -1;
            }
            return number_format($number, 2, ',', '.');
        });

        Str::macro('numberBrToEn', function ($number) {
            $value = str_replace('.', '', $number);
            return (float)str_replace(',', '.', $value);
        });

        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }    
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
