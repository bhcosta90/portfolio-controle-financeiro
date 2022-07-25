<?php

namespace App\Providers;

use App\Repository\Eloquent\{
    AccountEloquent,
    BankEloquent,
    CompanyEloquent,
    CustomerEloquent,
    PaymentEloquent,
    ReceiveEloquent,
    RecurrenceEloquent,
    TenantEloquent,
    TransactionEloquent
};
use App\Repository\Transactions\DatabaseTransaction;
use Core\Application\BankAccount\Modules\Account\Repository\AccountRepository;
use Core\Application\BankAccount\Modules\Bank\Repository\BankRepository;
use Core\Application\Charge\Modules\Payment\Repository\PaymentRepository;
use Core\Application\Charge\Modules\Receive\Repository\ReceiveRepository;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository;
use Core\Application\Tenant\Repository\TenantRepository;
use Core\Application\Transaction\Repository\TransactionRepository;
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
        $this->app->singleton(BankRepository::class, BankEloquent::class);
        $this->app->singleton(AccountRepository::class, AccountEloquent::class);
        $this->app->singleton(TransactionRepository::class, TransactionEloquent::class);
        $this->app->singleton(TenantRepository::class, TenantEloquent::class);
        $this->app->singleton(PaymentRepository::class, PaymentEloquent::class);
        $this->app->singleton(ReceiveRepository::class, ReceiveEloquent::class);
        $this->app->singleton(CompanyRepository::class, CompanyEloquent::class);
        $this->app->singleton(CustomerRepository::class, CustomerEloquent::class);
        $this->app->singleton(RecurrenceRepository::class, RecurrenceEloquent::class);
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
