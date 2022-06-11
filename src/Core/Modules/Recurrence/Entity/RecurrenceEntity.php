<?php

namespace Costa\Modules\Recurrence\Entity;

use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\Utils\CalculateDate;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\Input\InputIntObject;
use Costa\Shared\ValueObject\UuidObject;
use DateTime;

class RecurrenceEntity extends EntityAbstract
{
    public function __construct(
        protected InputNameObject $name,
        protected InputIntObject $days,
        protected ?UuidObject $id = null,
    ) {
        parent::__construct();
    }

    public function update(
        InputNameObject $name,
        InputIntObject $days,
    ) {
        $this->days = $days;
        $this->name = $name;
    }

    public function calculate(DateTime $date)
    {
        $modify = $this->days->value . " days";

        if ($this->days->value % 30 == 0) {
            $modify = $this->days->value / 30 . " month";
        }

        if ($this->days->value % 365 == 0) {
            $modify = $this->days->value / 365 . " year";
        }

        return $date->modify("+{$modify}");
    }
}
