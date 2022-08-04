<?php

namespace Core\Application\Transaction\UseCases;

use Core\Application\Transaction\Repository\TransactionRepository;
use Core\Shared\Interfaces\EventManagerInterface;

class ExecuteSchedulePaymentUseCase
{
    public function __construct(
        private TransactionRepository $transaction,
        private EventManagerInterface $event,
    ) {
        //
    }

    /**
     * @param DTO\ExecuteSchedulePayment\Input $input
     * @return DTO\ExecuteSchedulePayment\Output[]
     */
    public function handle(DTO\ExecuteSchedulePayment\Input $input): array
    {
        $limit = 50;
        $page = 0;
        $ret = [];
        do {
            $results = $this->transaction->getTransactionInDate($input->date, $limit, $page);
            foreach ($results->items() as $rs) {
                $obj = $this->transaction->toEntity($rs);
                $updated = $this->transaction->update($obj);
                $this->event->dispatch($obj->events);
                $ret[] = new DTO\ExecuteSchedulePayment\Output($updated);
                sleep(0.1);
            }
            $page++;
        } while (count($results->items()) == $limit);

        return $ret;
    }
}
