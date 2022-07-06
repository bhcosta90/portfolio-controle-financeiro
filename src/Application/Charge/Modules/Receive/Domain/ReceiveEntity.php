<?php

namespace Core\Application\Charge\Modules\Receive\Domain;

use Core\Application\Charge\Shared\Contracts\ChargePayInterface;
use Core\Application\Charge\Shared\Enums\ChargeStatusEnum;
use Core\Application\Charge\Modules\Receive\Events\{AddPayEvent, RemovePayEvent};
use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\EntityObject;
use Core\Shared\ValueObjects\Input\FloatInputObject;
use Core\Shared\ValueObjects\Input\NameInputObject;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;
use Exception;

class ReceiveEntity extends EntityAbstract implements ChargePayInterface
{
    protected array $events = [];

    protected function __construct(
        protected NameInputObject $title,
        protected ?NameInputObject $resume,
        protected EntityObject $customer,
        protected ?UuidObject $recurrence,
        protected FloatInputObject $value,
        protected ?FloatInputObject $pay,
        protected ChargeStatusEnum $status,
        protected UuidObject $group,
        protected DateTime $date,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        //
    }

    public static function create(
        string $title,
        ?string $resume,
        string $customer,
        ?string $recurrence,
        float $value,
        ?float $pay,
        string $group,
        string $date,
        ?int $status = null,
        ?string $id = null,
        ?string $createdAt = null,
    ) {
        return new self(
            title: new NameInputObject($title),
            resume: new NameInputObject($resume, true),
            customer: new EntityObject($customer, CustomerEntity::class),
            recurrence: $recurrence ? new UuidObject($recurrence) : null,
            value: new FloatInputObject($value),
            pay: new FloatInputObject($pay, true),
            status: $status ? ChargeStatusEnum::from($status) : ChargeStatusEnum::PENDING,
            group: new UuidObject($group),
            date: new DateTime($date),
            id: $id ? new UuidObject($id) : UuidObject::random(),
            createdAt: new DateTime($createdAt),
        );
    }

    public function update(
        string $title,
        ?string $resume,
        string $customer,
        ?string $recurrence,
        float $value,
        string $date,
    ) {
        $this->title = new NameInputObject($title);
        $this->resume = new NameInputObject($resume, true);
        $this->customer = new EntityObject($customer, CustomerEntity::class);
        $this->value = new FloatInputObject($value);
        $this->date = new DateTime($date);
        $this->recurrence = $recurrence ? new UuidObject($recurrence) : null;
    }

    public function pay(float $value, float $valueCharge): self
    {
        if ($value + $this->pay->value > $this->value->value) {
            throw new Exception('This payment is greater than the amount charged');
        }

        $this->status = ($this->pay->value + $value) == $valueCharge
            || $valueCharge == $this->value->value
            || !empty($this->recurrence)
            ? ChargeStatusEnum::COMPLETED
            : ChargeStatusEnum::PARTIAL;
        $this->pay = new FloatInputObject($value + $this->pay->value);
        $this->events[] = new AddPayEvent($this, new FloatInputObject($value));
        return $this;
    }

    public function cancel(float $value): self
    {
        $calc = $this->pay->value - $value;
        if ($calc < 0) {
            throw new Exception('This payment cannot be canceled as it leaves the charge amount less than 0');
        }

        $this->pay = new FloatInputObject($this->pay->value - $value, true);
        $this->status = $this->pay->value == 0 ? ChargeStatusEnum::PENDING : ChargeStatusEnum::PARTIAL;
        $this->events[] = new RemovePayEvent($this, new FloatInputObject($value));
        return $this;
    }
}
