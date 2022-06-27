<?php

namespace Core\Financial\Payment\Domain;

use Core\Financial\BankAccount\Domain\BankAccountEntity;
use Core\Financial\Payment\Enums\ChargeStatusEnum;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;

class PaymentEntity extends EntityAbstract
{
    protected bool $completed;
    protected ChargeStatusEnum $status = ChargeStatusEnum::PROCESSING;

    private function __construct(
        protected float $value,
        protected DateTime $date,
        protected ?BankAccountEntity $bankAccount,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public static function create(
        float $value,
        string $date,
        ?BankAccountEntity $bankAccount,
        ?string $id = null,
        ?string $createdAt = null,
    ) {
        $entity = new self(
            $value,
            new DateTime($date),
            $bankAccount,
            $id ? new UuidObject($id) : UuidObject::random(),
            new DateTime($createdAt),
        );

        $entity->completed = date('Y-m-d') >= $entity->date->format('Y-m-d');
        return $entity;
    }
}
