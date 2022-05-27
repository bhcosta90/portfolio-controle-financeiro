<?php

namespace App\Providers;

use App\Repositories\Eloquent\{AccountRepository, BankRepository, ChargePaymentRepository, ChargeReceiveRepository, ChargeRepository, CustomerRepository, PaymentRepository, RecurrenceRepository, RelationshipRepository, SupplierRepository};
use App\Repositories\Transactions\DatabaseTransaction;
use Costa\Modules\Account\Repository\{AccountRepositoryInterface, BankRepositoryInterface};
use Costa\Modules\Relationship\Repository\{CustomerRepositoryInterface, RelationshipRepositoryInterface, SupplierRepositoryInterface};
use Costa\Modules\Charge\Repository\{ChargePaymentRepositoryInterface, RecurrenceRepositoryInterface, ChargeReceiveRepositoryInterface, ChargeRepositoryInterface};
use Costa\Modules\Payment\Repository\PaymentRepositoryInterface;
use Costa\Shareds\Contracts\TransactionContract;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->singleton(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->singleton(TransactionContract::class, DatabaseTransaction::class);
        $this->app->singleton(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->singleton(BankRepositoryInterface::class, BankRepository::class);
        $this->app->singleton(RecurrenceRepositoryInterface::class, RecurrenceRepository::class);
        $this->app->singleton(RelationshipRepositoryInterface::class, RelationshipRepository::class);
        $this->app->singleton(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->singleton(ChargeReceiveRepositoryInterface::class, ChargeReceiveRepository::class);
        $this->app->singleton(ChargePaymentRepositoryInterface::class, ChargePaymentRepository::class);
        $this->app->singleton(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->singleton(ChargeRepositoryInterface::class, ChargeRepository::class);
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
