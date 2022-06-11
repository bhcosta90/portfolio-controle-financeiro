<?php

namespace Costa\Modules\Charge\Payment\UseCases;

use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Bank\Entity\BankEntity;
use Costa\Modules\Charge\Payment\Entity\ChargeEntity;
use Costa\Modules\Charge\Payment\Repository\ChargeRepositoryInterface;
use Costa\Modules\Charge\Utils\Enums\ChargeStatusEnum;
use Costa\Modules\Payment\Contracts\PaymentEventManagerContract;
use Costa\Modules\Payment\Entity\PaymentEntity;
use Costa\Modules\Payment\Repository\PaymentRepositoryInterface;
use Costa\Modules\Payment\Shared\Enums\PaymentType;
use Costa\Modules\Recurrence\Entity\RecurrenceEntity;
use Costa\Modules\Recurrence\Repository\RecurrenceRepositoryInterface;
use Costa\Shared\Contracts\TransactionContract;
use Costa\Shared\Utils\CalculateDate;
use Costa\Shared\ValueObject\ModelObject;
use Throwable;

class PaymentUseCase
{
    public function __construct(
        protected ChargeRepositoryInterface $repo,
        protected PaymentEventManagerContract $paymentEventManager,
        protected AccountRepositoryInterface $accountRepository,
        protected TransactionContract $transaction,
        protected PaymentRepositoryInterface $payment,
        protected RecurrenceRepositoryInterface $recurrence,
    ) {
        //
    }

    public function handle(DTO\Payment\Input $input): DTO\Payment\Output
    {
        /** @var ChargeEntity */
        $objCharge = $this->repo->find($input->id);
        $objCharge->pay($input->value, $input->charge === $objCharge->value->value);

        $account = $this->accountRepository->findByEntity($objCharge->supplier)->id();
        $bank = $input->bank
            ? $this->accountRepository->findByEntity(new ModelObject($input->bank, BankEntity::class))->id()
            : null;

        $objPayment = new PaymentEntity(
            relationship: $objCharge->supplier->id,
            charge: new ModelObject($objCharge->id(), $objCharge),
            date: $input->date,
            value: $input->value,
            accountFrom: $account,
            accountTo: $bank ? $bank : null,
            type: PaymentType::CREDIT,
            title: $objCharge->title,
        );
        
        try {
            $this->repo->update($objCharge);
            
            $this->payment->insert($objPayment);
            $objPayment->dispatch($this->paymentEventManager);

            if ($objCharge->status == ChargeStatusEnum::COMPLETED && $objCharge->recurrence) {
                /** @var RecurrenceEntity */
                $objRecurrence = $this->recurrence->find($objCharge->recurrence);

                $objNewCharge = new ChargeEntity(
                    title: $objCharge->title,
                    description: $objCharge->description,
                    supplier: $objCharge->supplier,
                    value: $objCharge->value,
                    date: $objRecurrence->calculate($objCharge->date),
                    base: $objCharge->base,
                    recurrence: $objCharge->recurrence,
                );

                $this->repo->insert($objNewCharge);
            }

            $this->transaction->commit();

            return new DTO\Payment\Output(
                relationship: $objPayment->relationship,
                charge_id: $objPayment->charge->id,
                charge_type: $objPayment->charge->type,
                date: $objPayment->date->format('Y-m-d'),
                value: $objPayment->value,
                accountFrom: $objPayment->accountFrom,
                accountTo: $objPayment->accountTo,
                id: $objPayment->id(),
                created_at: $objPayment->createdAt(),
                completed: $objPayment->completed,
            );
        } catch(Throwable $e){
            $this->transaction->rollback();
            throw $e;
        }
    }
}
