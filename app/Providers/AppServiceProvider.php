<?php

namespace App\Providers;

use App\Repositories\Eloquent\{
    CustomerRepository,
    SupplierRepository
};

use Costa\Modules\Relationship\Repositories\CustomerRepositoryInterface;
use Costa\Modules\Relationship\Repositories\SupplierRepositoryInterface;
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
        $this->app->singleton(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->singleton(SupplierRepositoryInterface::class, SupplierRepository::class);
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
