<?php

namespace Costa\Modules\Payment\UseCases;

use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Account\Repository\BankRepositoryInterface;
use Costa\Modules\Payment\PaymentEntity;
use Costa\Modules\Payment\Repository\PaymentRepositoryInterface;
use Costa\Shareds\Contracts\TransactionContract;
use Costa\Modules\Payment\UseCases\DTO\Payment\Input as PaymentInput;
use Throwable;

class PaymentFindAndPayUseCase
{
    public function __construct(
        private PaymentRepositoryInterface $payment,
        private AccountRepositoryInterface $account,
        private BankRepositoryInterface $bank,
        private TransactionContract $transaction,
    ) {
        //
    }

    public function exec(DTO\FindAndPay\Input $input)
    {
        try {
            /** @var PaymentEntity */
            $objPayment = $this->payment->find($input->id);
            $objPayment->completed();
            $this->payment->update($objPayment);

            $paymentUseCase = new PaymentUseCase(
                account: $this->account,
                payment: $this->payment,
                bank: $this->bank,
            );

            $paymentUseCase->exec(new PaymentInput(
                type: $objPayment->type,
                account: $input->account,
                accounts: $input->accounts,
                value: $input->value,
                bank: null,
            ));

            $this->transaction->commit();
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
