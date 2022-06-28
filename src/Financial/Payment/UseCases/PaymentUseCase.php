<?php

namespace Core\Financial\Payment\UseCases;

use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Payment\Domain\PaymentEntity;
use Core\Financial\Payment\Repository\PaymentRepositoryInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Throwable;

class PaymentUseCase
{
    public function __construct(
        private PaymentRepositoryInterface $payment,
        private AccountRepositoryInterface $account,
        private TransactionInterface $transaction,
    ) {
        //
    }

    public function handle(DTO\Payment\PaymentInput $input): DTO\Payment\PaymentOutput
    {
        /** @var PaymentEntity */
        $rs = $this->payment->find($input->id);
        if ($rs->completed()) {
            try {
                if (!empty($rs->accountFrom) && $rs->accountFrom) {
                    $this->account->sub($rs->accountFrom, $input->value);
                }

                if (!empty($rs->accountTo) && $rs->accountTo) {
                    $this->account->add($rs->accountTo, $input->value);
                }

                $ret = $this->payment->update($rs);
                $this->transaction->commit();
            } catch (Throwable $e) {
                $this->transaction->rollback();
                throw $e;
            }
        }

        return new DTO\Payment\PaymentOutput($ret ?? false);
    }
}
