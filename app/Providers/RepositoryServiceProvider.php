<?php

namespace App\Providers;

use App\Repository\Eloquent\{
    AccountBankEloquent,
    ChargePaymentEloquent,
    ChargeReceiveEloquent,
    CompanyEloquent,
    CustomerEloquent,
    PaymentEloquent,
    RecurrenceEloquent
};
use App\Repository\Transactions\DatabaseTransaction;
use Core\Application\AccountBank\Repository\AccountBankRepository;
use Core\Application\Charge\Modules\Payment\Repository\ChargePaymentRepository;
use Core\Application\Charge\Modules\Receive\Repository\ChargeReceiveRepository;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Payment\Repository\PaymentRepository;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository;
use Core\Shared\Interfaces\TransactionInterface;
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
        $this->app->singleton(TransactionInterface::class, DatabaseTransaction::class);
        $this->app->singleton(AccountBankRepository::class, AccountBankEloquent::class);
        $this->app->singleton(CustomerRepository::class, CustomerEloquent::class);
        $this->app->singleton(CompanyRepository::class, CompanyEloquent::class);
        $this->app->singleton(RecurrenceRepository::class, RecurrenceEloquent::class);
        $this->app->singleton(ChargePaymentRepository::class, ChargePaymentEloquent::class);
        $this->app->singleton(ChargeReceiveRepository::class, ChargeReceiveEloquent::class);
        $this->app->singleton(PaymentRepository::class, PaymentEloquent::class);
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
