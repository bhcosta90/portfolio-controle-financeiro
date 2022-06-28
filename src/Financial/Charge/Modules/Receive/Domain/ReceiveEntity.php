<?php

namespace Core\Financial\Charge\Modules\Receive\Domain;

use Core\Financial\Charge\Shared\Enums\ChargeStatusEnum;
use Core\Financial\Charge\Shared\Enums\ChargeTypeEnum;
use Core\Financial\Recurrence\Domain\RecurrenceEntity;
use Core\Financial\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;
use DomainException;
use Exception;

class ReceiveEntity extends EntityAbstract
{
    protected ChargeStatusEnum $status;
    
    private function __construct(
        protected UuidObject $group,
        protected float $value,
        protected CustomerEntity $customer,
        protected ChargeTypeEnum $type,
        protected DateTime $date,
        protected ?RecurrenceEntity $recurrence,
        protected float $pay,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public static function create(
        string $group,
        float $value,
        CustomerEntity $customer,
        int $type,
        string $date,
        ?RecurrenceEntity $recurrence,
        float $pay = 0,
        ?int $status = null,
        ?string $id = null,
        ?string $createdAt = null,
    ) {
        $obj = new self(
            new UuidObject($group),
            $value,
            $customer,
            ChargeTypeEnum::from($type),
            new DateTime($date),
            $recurrence,
            $pay,
            $id ? new UuidObject($id) : null,
            new DateTime($createdAt)
        );

        $obj->status = $status ? ChargeStatusEnum::from($status) : ChargeStatusEnum::PENDING;
        $obj->validate();
        return $obj;
    }

    public function update(
        float $value,
        CustomerEntity $customer,
        string $date,
        ?RecurrenceEntity $recurrence,
    ) {
        $this->value = $value;
        $this->customer = $customer;
        $this->recurrence = $recurrence;
        $this->date = new DateTime($date);
        $this->validate();
    }

    public function pay(float $pay, float $value)
    {
        if ($pay + $this->pay > $value) {
            throw new Exception('This payment is greater than the amount charged');
        }

        $this->status = ($this->pay + $pay) == $value ? ChargeStatusEnum::COMPLETED : ChargeStatusEnum::PARTIAL;
        return $this;
    }

    public function cancel(float $value)
    {
        if ($this->pay <= 0) {
            throw new Exception('This charge has not been paid');
        }

        $this->status = ($this->pay - $value) == 0 ? ChargeStatusEnum::PENDING : ChargeStatusEnum::PARTIAL;
        return $this;
    }

    protected function validate()
    {
        if ($this->value <= 0) {
            throw new DomainException('Payment amount cannot be free');
        }
    }
}
