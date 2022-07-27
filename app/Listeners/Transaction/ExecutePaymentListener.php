<?php

namespace App\Listeners\Transaction;

use App\Jobs\Transaction\ExecutePaymentJob;
use Carbon\Carbon;
use Core\Application\Transaction\Events\ExecutePaymentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExecutePaymentListener implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(ExecutePaymentEvent $event)
    {
        dispatch(new ExecutePaymentJob($event))->delay(Carbon::now()->addMinute());
    }
}
