<?php

namespace App\Providers;

use App\Helpers\PriceHelper;
use App\Manager\EventManager;
use Core\Report\Contracts\PriceInterface;
use Core\Shared\Interfaces\EventManagerInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EventManagerInterface::class, EventManager::class);

        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }    
        $this->app->singleton(PriceInterface::class, PriceHelper::class);
        Paginator::useBootstrap();
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
