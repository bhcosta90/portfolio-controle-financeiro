<?php

namespace Core\Application\BankAccount\Modules\Account\Domain;

use Core\Application\BankAccount\Modules\Account\Events\{AddValueEvent, RemoveValueEvent};
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Contracts\ValueInterface;
use Core\Shared\ValueObjects\{EntityObject, UuidObject};
use DateTime;

class AccountEntity extends EntityAbstract implements ValueInterface
{
    protected array $events;

    private function __construct(
        protected UuidObject $tenant,
        protected EntityObject $entity,
        protected float $value,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public static function create(
        string $tenant,
        string $entity_id,
        string|object $entity_type,
        float $value,
        ?string $id = null,
        ?string $createdAt = null,
    ): self {
        return new self(
            new UuidObject($tenant),
            new EntityObject($entity_id, $entity_type),
            $value,
            $id ? new UuidObject($id) : null,
            $createdAt ? new DateTime($createdAt) : null,
        );
    }

    public function addValue(float $value, string $idPayment)
    {
        $this->value += $value;
        $this->events[] = new AddValueEvent($this->id(), $value);
    }

    public function removeValue(float $value, string $idPayment)
    {
        $this->value -= $value;
        $this->events[] = new RemoveValueEvent($this->id(), $value);
    }
}
