<?php

namespace App\Listeners;

use Costa\Modules\Payment\UseCases\PaymentTransferUseCase;
use Costa\Modules\Payment\UseCases\DTO\PaymentTransfer\Input;
use Illuminate\Support\Facades\Log;

class PaymentListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(private PaymentTransferUseCase $useCase)
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event, $rs)
    {
        if ($rs['completed'] ?? false) {
            Log::info('Execute payment of event ' . $event);
            $this->useCase->handle(new Input($rs['account_from'], $rs['account_to'], $rs['value']));
        }
    }
}
