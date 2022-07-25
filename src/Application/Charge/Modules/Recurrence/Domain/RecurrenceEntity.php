<?php

namespace Core\Application\Charge\Modules\Recurrence\Domain;

use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\Input\IntInputObject;
use Core\Shared\ValueObjects\Input\NameInputObject;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;

class RecurrenceEntity extends EntityAbstract
{
    protected function __construct(
        protected UuidObject $tenant,
        protected NameInputObject $name,
        protected IntInputObject $days,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        //
    }

    public static function create(
        string $tenant,
        string $name,
        int $days,
        ?string $id = null,
        ?string $createdAt = null,
    ) {
        return new self(
            tenant: new UuidObject($tenant),
            name: new NameInputObject($name),
            days: new IntInputObject($days, false, 'days'),
            id: $id ? new UuidObject($id) : UuidObject::random(),
            createdAt: new DateTime($createdAt),
        );
    }

    public function update(
        string $name,
        int    $days,
    ) {
        $this->name = new NameInputObject($name);
        $this->days = new IntInputObject($days);
    }

    public function calculate(string $date = null): DateTime
    {
        $objDateTime = new DateTime($date);
        $objDateVerify = (new DateTime($date))->modify('last day of this month')->format('Y-m-d');

        if ($lastDay = $objDateVerify == $date) {
            $objDateTime = $objDateTime->modify('first day of this month');
        }

        $modify = $this->days->value . " days";

        if ($this->days->value % 30 == 0) {
            $modify = $this->days->value / 30 . " month";
        }

        if ($this->days->value % 365 == 0) {
            $modify = $this->days->value / 365 . " year";
        }

        $objDateTime->modify("+{$modify}");

        if ($lastDay) {
            $objDateTime = $objDateTime->modify('last day of this month');
        }

        return $objDateTime;
    }
}
