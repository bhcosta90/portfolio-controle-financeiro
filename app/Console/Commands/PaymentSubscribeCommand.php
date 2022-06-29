<?php

namespace App\Console\Commands;

use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Payment\Events\PayEvent;
use Core\Financial\Payment\Repository\PaymentRepositoryInterface;
use Core\Financial\Payment\UseCases\PaymentUseCase;
use Core\Financial\Payment\UseCases\DTO\Payment\PaymentInput;
use Core\Shared\Interfaces\TransactionInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class PaymentSubscribeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(PaymentUseCase $paymentUseCase)
    {
        Redis::psubscribe(['payment.execute.' . PayEvent::CACHE_PAY_EVENT . '.*'], function ($message) use ($paymentUseCase) {
            $data = json_decode($message, true);
            $paymentUseCase->handle(new PaymentInput(
                id: $data['id'],
                value: $data['value'],
                accountFromId: $data['account_from'],
                accountToId: $data['account_to'],
            ));
        });
    }
}
