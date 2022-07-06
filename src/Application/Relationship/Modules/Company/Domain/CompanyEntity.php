<?php

namespace Core\Application\Relationship\Modules\Company\Domain;

use Core\Application\Relationship\Modules\Company\Events\AddValueEvent;
use Core\Application\Relationship\Modules\Company\Events\RemoveValueEvent;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Contracts\ValueInterface;
use Core\Shared\ValueObjects\Input\FloatInputObject;
use Core\Shared\ValueObjects\Input\NameInputObject;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;

class CompanyEntity extends EntityAbstract implements ValueInterface
{
    protected array $events;

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
        ?string $id = null,
        ?string $createdAt = null,
    ): self {
        return new self(
            new UuidObject($tenant),
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
        $this->events[] = new AddValueEvent($this, new FloatInputObject($value), new UuidObject($idPayment));
    }

    public function removeValue(float $value, string $idPayment)
    {
        $this->value -= $value;
        $this->events[] = new RemoveValueEvent($this, new FloatInputObject($value), new UuidObject($idPayment));
    }
}
