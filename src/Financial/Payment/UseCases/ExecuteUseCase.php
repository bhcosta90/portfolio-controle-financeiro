<?php

namespace Core\Financial\Payment\UseCases;

use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Payment\Domain\PaymentEntity;
use Core\Financial\Payment\Repository\PaymentRepositoryInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Throwable;

class ExecuteUseCase
{
    public function __construct(
        private PaymentRepositoryInterface $payment,
        private AccountRepositoryInterface $account,
        private TransactionInterface $transaction,
    ) {
        //
    }

    public function handle(DTO\Execute\ExecuteInput $input): DTO\Execute\ExecuteOutput
    {
        $ret = [];

        /** @var PaymentEntity $rs */
        foreach ($this->payment->findPaymentExecuteByDate($input->date) as $rs) {
            if ($rs->completed()) {
                if (!empty($rs->accountFrom) && $rs->accountFrom) {
                    $this->account->sub($rs->accountFrom, $rs->value);
                }

                if (!empty($rs->accountTo) && $rs->accountTo) {
                    $this->account->add($rs->accountTo, $rs->value);
                }

                try {
                    $this->payment->update($rs);
                    $this->transaction->commit();
                    $ret[] = [
                        'success' => true,
                    ];
                } catch (Throwable $e) {
                    $this->transaction->rollback();
                    $ret[] = [
                        'success' => false,
                        'message' => $e->getMessage(),
                        'code' => $e->getCode(),
                    ];
                    throw $e;
                }
            }
        }

        return new DTO\Execute\ExecuteOutput($ret);
    }
}
