<?php

namespace Core\Application\Relationship\Modules\Company\Domain;

use Core\Application\BankAccount\Modules\Account\Domain\AccountEntity;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Contracts\ValueInterface;
use Core\Shared\ValueObjects\{Input\NameInputObject, UuidObject};
use DateTime;

class CompanyEntity extends EntityAbstract implements ValueInterface
{
    protected AccountEntity $account;

    private function __construct(
        protected UuidObject $tenant,
        protected NameInputObject $name,
        protected float $value,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public static function create(
        string $tenant,
        string $name,
        float $value = 0,
        ?string $account = null,
        ?string $id = null,
        ?string $createdAt = null,
    ): self {
        $obj = new self(
            new UuidObject($tenant),
            new NameInputObject($name, false, 'name'),
            $value,
            $id ? new UuidObject($id) : null,
            $createdAt ? new DateTime($createdAt) : null,
        );
        $obj->account = AccountEntity::create($tenant, $obj->id(), $obj, $value, $account);
        return $obj;
    }

    public function update(
        string $name,
    ) {
        $this->name = new NameInputObject($name, false, 'name');
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
