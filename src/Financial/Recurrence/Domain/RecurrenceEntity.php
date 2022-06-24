<?php

namespace Core\Financial\Recurrence\Domain;

use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\Input\NameInputObject;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;
use DomainException;

class RecurrenceEntity extends EntityAbstract
{
    private function __construct(
        protected NameInputObject $name,
        protected int $days,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
        $this->validated();
    }

    public static function create(
        string $name,
        int $days,
        ?string $id = null,
        ?string $createdAt = null,
    ): self {
        return new self(
            name: new NameInputObject($name),
            days: $days,
            id: $id ? new UuidObject($id) : null,
            createdAt: $createdAt ? new DateTime($createdAt) : null,
        );
    }

    public function update(
        string $name,
        int $days,
    ): self {
        $this->name = new NameInputObject($name);
        $this->days = $days;
        $this->validated();
        return $this;
    }

    public function calculate(string $date = null): DateTime
    {
        $objDateTime = new DateTime($date);
        $objDateVerify = (new DateTime($date))->modify('last day of this month')->format('Y-m-d');

        if ($lastDay = $objDateVerify == $date) {
            $objDateTime = $objDateTime->modify('first day of this month');
        }

        $modify = $this->days . " days";

        if ($this->days % 30 == 0) {
            $modify = $this->days / 30 . " month";
        }

        if ($this->days % 365 == 0) {
            $modify = $this->days / 365 . " year";
        }

        $objDateTime->modify("+{$modify}");

        if ($lastDay) {
            $objDateTime = $objDateTime->modify('last day of this month');
        }

        return $objDateTime;
    }

    private function validated()
    {
        if ($this->days < 1) {
            new DomainException('Recurrence must be at least 1 day');
        }
    }
}
