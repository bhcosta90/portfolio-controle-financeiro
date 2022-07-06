<?php

namespace Core\Application\Relationship\Modules\Customer\Domain;

use Core\Application\Relationship\Modules\Customer\Events\AddValueEvent;
use Core\Application\Relationship\Modules\Customer\Events\RemoveValueEvent;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Contracts\ValueInterface;
use Core\Shared\ValueObjects\Input\FloatInputObject;
use Core\Shared\ValueObjects\Input\NameInputObject;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;

class CustomerEntity extends EntityAbstract implements ValueInterface
{
    protected array $events;

    private function __construct(
        protected NameInputObject $name,
        protected float $value,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public static function create(
        string $name,
        float $value = 0,
        ?string $id = null,
        ?string $createdAt = null,
    ): self {
        return new self(
            new NameInputObject($name, false, 'name'),
            $value,
            $id ? new UuidObject($id) : null,
            $createdAt ? new DateTime($createdAt) : null,
        );
    }

    public function update(
        string $name,
    ) {
        $this->name = new NameInputObject($name, false, 'name');
    }

    public function addValue(float $value, string $idPayment)
    {
        $this->value += $value;
        $this->events[] = new AddValueEvent($this, new FloatInputObject($value));
    }

    public function removeValue(float $value, string $idPayment)
    {
        $this->value -= $value;
        $this->events[] = new RemoveValueEvent($this, new FloatInputObject($value));
    }
}
