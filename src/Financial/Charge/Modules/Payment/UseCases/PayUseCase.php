<?php

namespace Core\Financial\Charge\Modules\Payment\UseCases;

use App\Models\BankAccount;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface;
use Core\Financial\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Financial\Charge\Modules\Payment\Repository\PaymentRepositoryInterface;
use Core\Financial\Payment\Domain\PaymentEntity;
use Core\Financial\Payment\Repository\PaymentRepositoryInterface as RepositoryPaymentRepositoryInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Throwable;

class PayUseCase
{
    public function __construct(
        private PaymentRepositoryInterface $repo,
        private RepositoryPaymentRepositoryInterface $payment,
        private BankAccountRepositoryInterface $account,
        private TransactionInterface $transaction,
    ) {
        //
    }

    public function handle(DTO\Pay\PayInput $input): DTO\Pay\PayOutput
    {
        /** @var Entity */
        $obj = $this->repo->find($input->id);
        $obj->pay($input->pay, $input->value);
        $objBankAccount = $input->bankAccountId ? $this->account->find($input->bankAccountId) : null;

        $objPayment = PaymentEntity::create(
            $input->pay,
            $input->date,
            $objBankAccount
        );

        try {
            $this->repo->update($obj);
            $this->payment->insert($objPayment);
            $this->transaction->commit();

            return new DTO\Pay\PayOutput(
                id: $obj->id(),
                value: $input->value,
                pay: $input->pay,
                completed: $objPayment->completed,
                status: $objPayment->status->value,
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
