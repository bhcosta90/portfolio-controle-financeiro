<?php

namespace Core\Application\Payment\Domain;

use Core\Application\Payment\Shared\Enums\PaymentStatusEnum;
use Core\Application\Payment\Shared\Enums\PaymentTypeEnum;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\EntityObject;
use Core\Shared\ValueObjects\Input\FloatInputObject;
use Core\Shared\ValueObjects\Input\NameInputObject;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;

class PaymentEntity extends EntityAbstract
{
    protected array $events = [];

    protected float $bankValue = 0;

    private function __construct(
        protected UuidObject        $tenant,
        protected ?EntityObject     $relationship,
        protected NameInputObject   $title,
        protected ?NameInputObject  $resume,
        protected ?NameInputObject  $name,
        protected ?EntityObject     $charge,
        protected ?UuidObject       $bank,
        protected FloatInputObject  $value,
        protected PaymentStatusEnum $status,
        protected PaymentTypeEnum   $type,
        protected DateTime          $date,
        protected ?UuidObject       $id = null,
        protected ?DateTime         $createdAt = null,
    )
    {
        parent::__construct();
    }

    public static function create(
        string        $tenant,
        ?EntityObject $relationship,
        ?EntityObject $charge,
        string        $title,
        ?string       $resume,
        ?string       $name,
        ?string       $bank,
        float         $value,
        ?int          $status,
        int           $type,
        string        $date = null,
        ?string       $id = null,
        ?string       $createdAt = null,
    )
    {
        $objDate = new DateTime($date);
        if (empty($date) || $objDate->format('Y-m-d') == (new DateTime)->format('Y-m-d')) {
            $objDate = (new DateTime())->modify('+1 minute');
        } else {
            $objDate->setTime(10, 00, 00);
        }

        return new self(
            tenant: new UuidObject($tenant),
            relationship: $relationship,
            charge: $charge,
            title: new NameInputObject($title),
            resume: new NameInputObject($resume, true),
            name: new NameInputObject($name, true),
            bank: $bank ? new UuidObject($bank) : null,
            value: new FloatInputObject($value, false, 'value'),
            status: $status ? PaymentStatusEnum::from($status) : PaymentStatusEnum::PENDING,
            type: PaymentTypeEnum::from($type),
            date: $objDate,
            id: $id ? new UuidObject($id) : null,
            createdAt: new DateTime($createdAt),
        );
    }

    public function complete()
    {
        $this->status = PaymentStatusEnum::PROCESSED;
    }

    public function bankValue($value)
    {
        $this->bankValue = $value;
    }
}
