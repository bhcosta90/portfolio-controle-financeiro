<?php

namespace Costa\Modules\Charge\Entities;

use Costa\Modules\Charge\Shareds\Enums\Status;
use Costa\Shareds\Abstracts\EntityAbstract;
use Costa\Shareds\Exceptions\DomainValidationException;
use Costa\Shareds\Validations\DomainValidation;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\Input\InputValueObject;
use Costa\Shareds\ValueObjects\ModelObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;

abstract class ChargeEntity extends EntityAbstract
{
    protected Status $status = Status::PENDING;
    protected InputValueObject $payValue;

    public function __construct(
        protected InputNameObject $title,
        protected ?InputNameObject $description,
        protected ModelObject $relationship,
        protected InputValueObject $value,
        protected DateTime $date,
        protected UuidObject $base,
        protected ?DateTime $dateStart = null,
        protected ?DateTime $dateFinish = null,
        protected ?UuidObject $recurrence = null,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();

        if ($this->dateStart == null) {
            $this->dateStart = $this->date;
        }

        if ($this->dateFinish == null) {
            $this->dateFinish = $this->date;
        }
        
        $this->payValue = new InputValueObject(null, true);

    }

    public function update(
        InputNameObject $title,
        ?InputNameObject $description,
        ModelObject $relationship,
        InputValueObject $value,
        DateTime $date,
        ?UuidObject $recurrence = null,
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->relationship = $relationship;
        $this->value = $value;
        $this->date = $date;
        $this->recurrence = $recurrence;
    }

    public function pay(float $transaction, $forceCompleted = false)
    {
        $varPayValue = $this->payValue->value + $transaction;

        if ($varPayValue > $this->value->value) {
            throw new DomainValidationException("Payment amount is higher than the billing amount");
        }

        $this->status = $varPayValue == $this->value->value || $forceCompleted ? Status::COMPLETED : Status::PARTIAL;
        $this->setValuePay($varPayValue);
    }

    public function setValuePay($value)
    {
        $this->payValue = new InputValueObject($value, true);
        return $this;
    }
}
