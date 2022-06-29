<?php

namespace Core\Financial\Charge\Modules\Payment\UseCases;

use Core\Financial\Account\Domain\AccountEntity;
use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface;
use Core\Financial\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Financial\Charge\Modules\Payment\Repository\PaymentRepositoryInterface;
use Core\Financial\Charge\Shared\Enums\ChargeStatusEnum;
use Core\Financial\Payment\Domain\PaymentEntity;
use Core\Financial\Payment\Repository\PaymentRepositoryInterface as RepositoryPaymentRepositoryInterface;
use Core\Shared\Interfaces\PublishManagerInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\ValueObjects\EntityObject;
use Throwable;

class PayUseCase
{
    public function __construct(
        private PaymentRepositoryInterface $repo,
        private RepositoryPaymentRepositoryInterface $payment,
        private BankAccountRepositoryInterface $bankAccount,
        private TransactionInterface $transaction,
        private AccountRepositoryInterface $account,
        private PublishManagerInterface $event,
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
            $this->account->find($obj->company->id(), get_class($obj->company)),
            $objBankAccount,
        );

        try {
            $this->repo->update($obj);
            $this->payment->insert($objPayment);

            if ($obj->status == ChargeStatusEnum::COMPLETED && $obj->recurrence) {
                $objNewCharge = Entity::create(
                    (string) $obj->group,
                    $obj->value,
                    $obj->company,
                    $obj->type->value,
                    $obj->recurrence->calculate($obj->date->format('Y-m-d'))->format('Y-m-d'),
                    $obj->recurrence
                );

                $this->repo->insert($objNewCharge);
            }

            $this->transaction->commit();
            $this->event->dispatch($objPayment->events);
            return new DTO\Pay\PayOutput(
                id: $obj->id(),
                value: $input->value,
                pay: $input->pay,
                completed: $objPayment->completed,
                status: $objPayment->status->value,
                charge: $objNewCharge ?? null,
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
