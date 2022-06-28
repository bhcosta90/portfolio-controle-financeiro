<?php

namespace Core\Financial\Charge\Modules\Receive\UseCases;

use Core\Financial\Account\Domain\AccountEntity;
use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface;
use Core\Financial\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Financial\Charge\Modules\Receive\Repository\ReceiveRepositoryInterface;
use Core\Financial\Payment\Domain\PaymentEntity;
use Core\Financial\Payment\Repository\PaymentRepositoryInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\ValueObjects\EntityObject;
use Throwable;

class PayUseCase
{
    public function __construct(
        private ReceiveRepositoryInterface $repo,
        private PaymentRepositoryInterface $payment,
        private BankAccountRepositoryInterface $bankAccount,
        private TransactionInterface $transaction,
        private AccountRepositoryInterface $account,
    ) {
        //
    }

    public function handle(DTO\Pay\PayInput $input): DTO\Pay\PayOutput
    {
        /** @var Entity */
        $obj = $this->repo->find($input->id);
        $obj->pay($input->pay, $input->value);
        $objBankAccount = $input->bankAccountId 
            ? $this->account->find(($o = $this->bankAccount->find($input->bankAccountId))->id(), get_class($o))
            : null;

        $objPayment = PaymentEntity::create(
            $input->pay,
            $input->date,
            new EntityObject($obj->id, $obj),
            $this->account->find($obj->id(), get_class($obj)),
            $objBankAccount,
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
