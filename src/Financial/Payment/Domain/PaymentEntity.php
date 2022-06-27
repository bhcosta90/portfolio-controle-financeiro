<?php

namespace Core\Financial\Payment\Domain;

use Core\Financial\BankAccount\Domain\BankAccountEntity;
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
        protected EntityObject $entity,
        protected ?BankAccountEntity $bankAccount,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public static function create(
        float $value,
        string $date,
        EntityObject $entity,
        ?BankAccountEntity $bankAccount,
        ?string $id = null,
        ?string $createdAt = null,
    ) {
        $entity = new self(
            $value,
            new DateTime($date),
            $entity,
            $bankAccount,
            $id ? new UuidObject($id) : UuidObject::random(),
            new DateTime($createdAt),
        );

        $entity->completed = date('Y-m-d') >= $entity->date->format('Y-m-d');

        if ($entity->completed) {
            $entity->events[] = new PayEvent($entity);
        }

        return $entity;
    }
}
