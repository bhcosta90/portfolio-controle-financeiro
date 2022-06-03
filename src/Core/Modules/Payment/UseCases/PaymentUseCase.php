<?php

namespace Costa\Modules\Payment\UseCases;

use Costa\Modules\Payment\Contracts\PaymentEventManagerContract;
use Costa\Modules\Payment\Entity\PaymentEntity;
use Costa\Modules\Payment\Events\PaymentEvent;
use Costa\Modules\Payment\Repository\PaymentRepositoryInterface;
use Costa\Shared\Contracts\TransactionContract;
use Exception;

class PaymentUseCase
{
    public function __construct(
        protected TransactionContract $transaction,
        protected PaymentRepositoryInterface $payment,
        protected PaymentEventManagerContract $paymentEventManager
    ) {
        //
    }

    public function handle(DTO\Payment\Input $input): DTO\Payment\Output
    {
        try {
            foreach ($input->id as $rs) {
                /** @var PaymentEntity */
                $objPayment = $this->payment->find($rs);
                $objPayment->completed();
                $this->paymentEventManager->dispatch(new PaymentEvent($objPayment));
                $this->payment->update($objPayment);
            }
            $this->transaction->commit();
        } catch (Exception $e) {
            $this->transaction->rollback();
            throw $e;
        }

        return new DTO\Payment\Output(true);
    }
}
