<?php

namespace Costa\Modules\Charge\Receive\Entity;

use Costa\Modules\Charge\Abstracts\ChargeAbstract;
use Costa\Modules\Charge\Utils\Enums\ChargeStatusEnum;
use Costa\Modules\Charge\Utils\Enums\ChargeTypeEnum;
use Costa\Shared\Validations\Exceptions\DomainValidationException;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\Input\InputValueObject;
use Costa\Shared\ValueObject\ModelObject;
use Costa\Shared\ValueObject\UuidObject;
use DateTime;

class ChargeEntity extends ChargeAbstract
{
    protected ChargeTypeEnum $type = ChargeTypeEnum::CREDIT;

    public function __construct(
        protected InputNameObject $title,
        protected ?InputNameObject $description,
        protected ModelObject $customer,
        protected InputValueObject $value,
        protected DateTime $date,
        protected UuidObject $base,
        protected ChargeStatusEnum $status = ChargeStatusEnum::PENDING,
        protected ?DateTime $dateStart = null,
        protected ?DateTime $dateFinish = null,
        protected ?UuidObject $recurrence = null,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
        protected ?InputValueObject $payValue = null,
    ) {
        parent::__construct();

        if ($this->dateStart == null) {
            $this->dateStart = $this->date;
        }

        if ($this->dateFinish == null) {
            $this->dateFinish = $this->date;
        }
    }

    public function update(
        InputNameObject $title,
        ?InputNameObject $description,
        ModelObject $customer,
        InputValueObject $value,
        DateTime $date,
        ?UuidObject $recurrence = null,
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->customer = $customer;
        $this->value = $value;
        $this->date = $date;
        $this->recurrence = $recurrence;
    }
}
