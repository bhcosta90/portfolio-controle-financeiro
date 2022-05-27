<?php

namespace Costa\Modules\Payment;

use Costa\Modules\Payment\Shareds\Enums\Type;
use Costa\Shareds\Abstracts\EntityAbstract;
use Costa\Shareds\ValueObjects\Input\InputValueObject;
use Costa\Shareds\ValueObjects\ModelObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;

class PaymentEntity extends EntityAbstract
{
    protected bool $completed;

    public function __construct(
        protected ModelObject $relationship,
        protected ModelObject $charge,
        protected InputValueObject $value,
        protected DateTime $schedule,
        protected Type $type,
        protected UuidObject $account,
        protected ?UuidObject $bank,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();

        $this->validated();
    }

    protected function validated()
    {
        $this->completed = true;

        if ($this->schedule && $this->schedule->format('Ymd') > (new DateTime())->format('Ymd')) {
            $this->completed = false;
        }
    }

    public function completed()
    {
        $this->completed = true;
    }
}
