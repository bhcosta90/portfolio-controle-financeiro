<?php

namespace Core\Application\Charge\Modules\Payment\Domain;

use Core\Application\Charge\Shared\Contracts\ChargePayInterface;
use Core\Application\Charge\Shared\Enums\ChargeStatusEnum;
use Core\Application\Charge\Shared\Exceptions\ChargeException;
use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\EntityObject;
use Core\Shared\ValueObjects\Input\FloatInputObject;
use Core\Shared\ValueObjects\Input\NameInputObject;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;

class PaymentEntity extends EntityAbstract implements ChargePayInterface
{
    protected function __construct(
        protected UuidObject $tenant,
        protected NameInputObject $title,
        protected ?NameInputObject $resume,
        protected EntityObject $company,
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
        string $tenant,
        string $title,
        ?string $resume,
        string $company,
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
            tenant: new UuidObject($tenant),
            title: new NameInputObject($title),
            resume: new NameInputObject($resume, true),
            company: new EntityObject($company, CompanyEntity::class),
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
        string  $title,
        ?string $resume,
        string  $company,
        ?string $recurrence,
        float   $value,
        string  $date,
    ) {
        $this->title = new NameInputObject($title);
        $this->resume = new NameInputObject($resume, true);
        $this->company = new EntityObject($company, CompanyEntity::class);
        $this->value = new FloatInputObject($value);
        $this->date = new DateTime($date);
        $this->recurrence = $recurrence ? new UuidObject($recurrence) : null;
    }

    public function pay(float $value): float
    {
        if ($value + $this->pay->value > $this->value->value) {
            throw new ChargeException('The payment is greater than the amount of the account payable');
        }
        $ret = ($value + $this->pay->value) - $this->value->value;
        $this->status = ChargeStatusEnum::COMPLETED;
        $this->pay = new FloatInputObject($value + $this->pay->value);
        return abs($ret);
    }

    public function cancel(float $value): self
    {
        $calc = $this->pay->value - $value;
        if ($calc < 0) {
            throw new ChargeException('This payment cannot be canceled as it leaves the charge amount less than 0');
        }

        $this->pay = new FloatInputObject($this->pay->value - $value, true);
        $this->status = ChargeStatusEnum::PENDING;
        return $this;
    }
}
