<?php

namespace Core\Financial\Payment\Domain;

use Core\Financial\Account\Domain\AccountEntity;
use Core\Financial\Payment\Enums\ChargeStatusEnum;
use Core\Financial\Payment\Events\PayEvent;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\EntityObject;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;

class PaymentEntity extends EntityAbstract
{
    protected bool $completed;

    protected ChargeStatusEnum $status = ChargeStatusEnum::PROCESSING;

    protected array $events = [];

    private function __construct(
        protected float $value,
        protected DateTime $date,
        protected ?EntityObject $entity,
        protected ?AccountEntity $accountFrom,
        protected ?AccountEntity $accountTo,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public static function create(
        float $value,
        string $date,
        ?EntityObject $entity,
        ?AccountEntity $accountFrom,
        ?AccountEntity $accountTo,
        ?string $id = null,
        ?string $createdAt = null,
    ) {
        $entity = new self(
            $value,
            new DateTime($date),
            $entity,
            $accountFrom,
            $accountTo,
            $id ? new UuidObject($id) : UuidObject::random(),
            new DateTime($createdAt),
        );

        $entity->completed = date('Y-m-d') >= $entity->date->format('Y-m-d');

        if ($entity->completed && empty($id)) {
            $entity->events[] = new PayEvent($entity);
        }

        return $entity;
    }

    public function completed()
    {
        $this->status = ChargeStatusEnum::PROCESSED;
        return $this->completed;
    }
}
