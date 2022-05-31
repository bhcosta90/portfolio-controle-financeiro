<?php

namespace Costa\Modules\Payment\Entity;

use Costa\Modules\Payment\Shared\Enums\PaymentType;
use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\ValueObject\UuidObject;
use DateTime;

class PaymentEntity extends EntityAbstract
{
    protected bool $completed;

    public function __construct(
        protected string $relationship,
        protected string $charge,
        protected DateTime $date,
        protected float $value,
        protected PaymentType $type,
        protected ?string $accountFrom = null,
        protected ?string $accountTo = null,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
        $this->completed = (new DateTime())->format('Y-m-d') >= $this->date->format('Y-m-d');
    }

    public function completed($completed = true)
    {
        $this->completed = $completed;
    }
}
