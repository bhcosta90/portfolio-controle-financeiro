<?php

namespace App\Providers;

use App\Events\PaymentEventManager;

use App\Repositories\Eloquent\{
    AccountRepository,
    BankRepository,
    ChargeReceiveRepository,
    ChargePaymentRepository,
    CustomerRepository,
    PaymentRepository,
    RecurrenceRepository,
    SupplierRepository
};
use App\Repositories\Transactions\DatabaseTransaction;
use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Bank\Repository\BankRepositoryInterface;
use Costa\Modules\Charge\Receive\Repository\ChargeRepositoryInterface as ReceiveRepositoryInterface;
use Costa\Modules\Charge\Payment\Repository\ChargeRepositoryInterface as PaymentRepositoryInterface;
use Costa\Modules\Payment\Contracts\PaymentEventManagerContract;
use Costa\Modules\Payment\Repository\PaymentRepositoryInterface as RepositoryPaymentRepositoryInterface;
use Costa\Modules\Recurrence\Repository\RecurrenceRepositoryInterface;
use Costa\Modules\Relationship\Customer\Repository\CustomerRepositoryInterface;
use Costa\Modules\Relationship\Supplier\Repository\SupplierRepositoryInterface;
use Costa\Shared\Contracts\TransactionContract;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
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
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        Paginator::useBootstrap();
        
        $this->app->singleton(TransactionContract::class, DatabaseTransaction::class);
        $this->app->singleton(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->singleton(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->singleton(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->singleton(RecurrenceRepositoryInterface::class, RecurrenceRepository::class);
        $this->app->singleton(BankRepositoryInterface::class, BankRepository::class);
        $this->app->singleton(ReceiveRepositoryInterface::class, ChargeReceiveRepository::class);
        $this->app->singleton(PaymentRepositoryInterface::class, ChargePaymentRepository::class);
        $this->app->singleton(PaymentEventManagerContract::class, PaymentEventManager::class);
        $this->app->singleton(RepositoryPaymentRepositoryInterface::class, PaymentRepository::class);
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
