<?php

namespace Core\Financial\Payment\UseCases;

use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Payment\Domain\PaymentEntity;
use Core\Financial\Payment\Repository\PaymentRepositoryInterface;
use Core\Shared\Interfaces\EventManagerInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Throwable;

class ExecuteUseCase
{
    public function __construct(
        private PaymentRepositoryInterface $payment,
        private EventManagerInterface $event,
    ) {
        //
    }

    public function handle(DTO\Execute\ExecuteInput $input): DTO\Execute\ExecuteOutput
    {
        $ret = [];

        foreach ($this->payment->findPaymentExecuteByDate($input->date) as $rs) {
            $this->event->dispatch($rs->events);
            $ret[] = $rs->completed;
        }

        return new DTO\Execute\ExecuteOutput($ret);
    }
}
