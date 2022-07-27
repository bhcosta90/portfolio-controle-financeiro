<?php

namespace App\Listeners\Transaction;

use Core\Application\Transaction\Events\ExecutePaymentEvent;
use Core\Application\Transaction\UseCases\ExecuteUseCase;
use Core\Application\Transaction\UseCases\DTO\Execute\Input;

class ExecutePaymentListener
{
    public function __construct(private ExecuteUseCase $executeUseCase)
    {
        //
    }

    public function handle(ExecutePaymentEvent $event)
    {
        $data = $event->payload();
        $this->executeUseCase->handle(new Input($data['tenant'], $data['id']));
    }
}
