<?php

namespace App\Listeners;

use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Illuminate\Support\Facades\Log;

class PaymentListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(private AccountRepositoryInterface $accountRepository)
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
            $rs['account_from'] ? $this->accountRepository->decrementValue($rs['account_from'], $rs['value']) : null;
            $rs['account_to'] ? $this->accountRepository->incrementValue($rs['account_to'], $rs['value']) : null;
        }
    }
}
