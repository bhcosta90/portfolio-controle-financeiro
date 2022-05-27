<?php

namespace Costa\Modules\Charge\UseCases\Charge;

use Costa\Modules\Charge\Entities\ChargeEntity;
use Costa\Modules\Charge\Shareds\Enums\Status;
use Costa\Modules\Payment\PaymentEntity;
use Costa\Modules\Payment\UseCases\PaymentUseCase;
use Costa\Modules\Payment\UseCases\DTO\Payment\Input as PaymentInput;
use Costa\Modules\Payment\Shareds\Enums\Type;
use Costa\Shareds\ValueObjects\Input\InputValueObject;
use Costa\Shareds\ValueObjects\ModelObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;
use Throwable;

abstract class ChargePaymentUseCase
{
    public function exec(DTO\Payment\Input $input): DTO\Payment\Output
    {
        /** @var ChargeEntity */
        $objCharge = $this->repo->find($input->id);
        $objAccount = $this->account->find($objCharge->relationship);
        $idBank = $input->bank ? $this->bank->find($input->bank)->id : null;
        
        $objPayment = new PaymentEntity(
            relationship: $objCharge->relationship,
            charge: new ModelObject(id: $objCharge->id(), type: $objCharge),
            value: new InputValueObject($input->valuePay),
            schedule: new DateTime($input->dateSchedule),
            type: Type::from($objCharge->type->value),
            account: new UuidObject($objAccount->model->id),
            bank: $idBank
        );
        
        try {
            $objCharge->pay(
                $input->valuePay,
                $input->valueCharge == ($objCharge->value->value - $objCharge->payValue->value)
            );

            $this->repo->update($objCharge);
            if ($objCharge->status == Status::COMPLETED && $objCharge->recurrence) {
                $objRecurrence = $this->recurrence->find($objCharge->recurrence);
                $classCharge = get_class($objCharge);
                $newCharge = new $classCharge(
                    title: $objCharge->title,
                    description: $objCharge->description,
                    relationship: $objCharge->relationship,
                    value: $objCharge->value,
                    date: $objCharge->date->modify(sprintf('%s days', $objRecurrence->days)),
                    base: $objCharge->base,
                );
                
                $this->repo->insert($newCharge);
            }

            $objPayment = $this->payment->insert($objPayment);

            if ($objPayment->completed) {
                $paymentUseCase = new PaymentUseCase(
                    account: $this->account,
                    payment: $this->payment,
                    bank: $this->bank,
                );

                $paymentUseCase->exec(new PaymentInput(
                    type: $objPayment->type,
                    account: $objCharge->relationship,
                    accounts: $input->accounts,
                    bank: $input->bank,
                    value: new InputValueObject($input->valuePay)
                ));
            }

            $this->transaction->commit();
            return new DTO\Payment\Output(success: true);
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
