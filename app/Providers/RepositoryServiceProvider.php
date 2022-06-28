<?php

namespace App\Providers;

use App\Repository\Eloquent\{
    AccountEloquentRepository,
    BankAccountEloquentRepository,
    CompanyEloquentRepository,
    CustomerEloquentRepository,
    ReceiveEloquentRepository,
    RecurrenceEloquentRepository
};
use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface;
use Core\Financial\Charge\Modules\Receive\Repository\ReceiveRepositoryInterface;
use Core\Financial\Recurrence\Repository\RecurrenceRepositoryInterface;
use Core\Financial\Relationship\Modules\Company\Repository\CompanyRepositoryInterface;
use Core\Financial\Relationship\Modules\Customer\Repository\CustomerRepositoryInterface;
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
        $this->app->singleton(CompanyRepositoryInterface::class, CompanyEloquentRepository::class);
        $this->app->singleton(CustomerRepositoryInterface::class, CustomerEloquentRepository::class);
        $this->app->singleton(AccountRepositoryInterface::class, AccountEloquentRepository::class);
        $this->app->singleton(BankAccountRepositoryInterface::class, BankAccountEloquentRepository::class);
        $this->app->singleton(RecurrenceRepositoryInterface::class, RecurrenceEloquentRepository::class);
        $this->app->singleton(ReceiveRepositoryInterface::class, ReceiveEloquentRepository::class);
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
