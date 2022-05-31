<?php

namespace Costa\Modules\Payment\Entity;

use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\ValueObject\ModelObject;
use Costa\Shared\ValueObject\UuidObject;
use DateTime;

class PaymentEntity extends EntityAbstract
{
    public function __construct(
        protected ModelObject $relationship,
        protected ModelObject $charge,
        protected DateTime $date,
        protected float $value,
        protected array $accountFrom,
        protected array $accountTo,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }
}
