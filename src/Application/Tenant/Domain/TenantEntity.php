<?php

namespace Core\Application\Tenant\Domain;

use Core\Application\BankAccount\Modules\Account\Domain\AccountEntity;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Contracts\ValueInterface;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;

class TenantEntity extends EntityAbstract implements ValueInterface
{
    protected AccountEntity $account;

    private function __construct(
        protected float $value,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public static function create(
        float $value = 0,
        ?string $id = null,
        ?string $account = null,
        ?string $createdAt = null,
    ): self {
        $obj = new self(
            $value,
            $id ? new UuidObject($id) : null,
            $createdAt ? new DateTime($createdAt) : null,
        );

        $obj->account = AccountEntity::create($obj->id(), $obj->id(), $obj, $value, $account);
        return $obj;
    }

    public function addValue(float $value, string $idPayment)
    {
        $this->value += $value;
        $this->account->addValue($value, $idPayment);
    }

    public function removeValue(float $value, string $idPayment)
    {
        $this->value -= $value;
        $this->account->removeValue($value, $idPayment);
    }
}
