<?php

namespace App\Providers;

use App\Repository\Eloquent\{
    AccountEloquentRepository,
    BankAccountEloquentRepository,
    CompanyEloquentRepository,
    CustomerEloquentRepository,
    ChargeReceiveEloquentRepository,
    ChargePaymentEloquentRepository,
    PaymentEloquentRepository,
    RecurrenceEloquentRepository
};
use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface;
use Core\Financial\Charge\Modules\Payment\Repository\PaymentRepositoryInterface as ChargePaymentRepositoryInterface;
use Core\Financial\Charge\Modules\Receive\Repository\ReceiveRepositoryInterface as ChargeReceiveRepositoryInterface;
use Core\Financial\Payment\Repository\PaymentRepositoryInterface;
use Core\Financial\Recurrence\Repository\RecurrenceRepositoryInterface;
use Core\Financial\Relationship\Modules\Company\Repository\CompanyRepositoryInterface;
use Core\Financial\Relationship\Modules\Customer\Repository\CustomerRepositoryInterface;
use Core\Shared\Interfaces\PublishManagerInterface;
use Illuminate\Support\ServiceProvider;
use App\Events\RedisPublishManager;

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
        $this->app->singleton(ReceiveRepositoryInterface::class, ChargeReceiveEloquentRepository::class);
        $this->app->singleton(ChargeReceiveRepositoryInterface::class, ChargeReceiveEloquentRepository::class);
        $this->app->singleton(ChargePaymentRepositoryInterface::class, ChargePaymentEloquentRepository::class);
        $this->app->singleton(PaymentRepositoryInterface::class, PaymentEloquentRepository::class);

        if(!$this->app->environment('testing')) {
            $this->app->singleton(PublishManagerInterface::class, RedisPublishManager::class);
        }
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
