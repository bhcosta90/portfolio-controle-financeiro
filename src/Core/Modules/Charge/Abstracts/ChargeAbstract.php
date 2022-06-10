<?php

namespace Costa\Modules\Charge\Abstracts;

use Costa\Modules\Charge\Utils\Enums\ChargeStatusEnum;
use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\Validations\Exceptions\DomainValidationException;
use Costa\Shared\ValueObject\Input\InputValueObject;

abstract class ChargeAbstract extends EntityAbstract
{
    public function pay(float $transaction, $forceCompleted = false)
    {
        if ($this->status == ChargeStatusEnum::COMPLETED) {
            throw new DomainValidationException("This charge is now complete.");
        }

        $varPayValue = $this->payValue->value + $transaction;

        if ($varPayValue > $this->value->value) {
            throw new DomainValidationException("Payment amount is higher than the billing amount");
        }

        $this->status = $varPayValue == $this->value->value || $forceCompleted
            ? ChargeStatusEnum::COMPLETED
            : ChargeStatusEnum::PARTIAL;

        $this->payValue = new InputValueObject($varPayValue);
    }

    public function payCancel(float $value){
        $valuePayActual = $this->payValue->value;
        $valuePayCalculate = $valuePayActual - $value;

        $this->status = $valuePayCalculate <= 0 ? ChargeStatusEnum::PENDING : ChargeStatusEnum::PARTIAL;
        $this->payValue = new InputValueObject($valuePayCalculate, true);
    }
}