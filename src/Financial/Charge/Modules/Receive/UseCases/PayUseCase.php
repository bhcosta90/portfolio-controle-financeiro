<?php

namespace Core\Financial\Charge\Modules\Receive\UseCases;

use Core\Financial\Account\Domain\AccountEntity;
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
            $model = new EntityObject($obj->id, $obj),
            AccountEntity::create($model, 50),
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
