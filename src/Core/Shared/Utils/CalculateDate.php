<?php

namespace Costa\Shared\Utils;

use DateTime;
use Exception;

class CalculateDate
{
    public function __construct(private DateTime $date, private int $days)
    {
        if ($days < 0) {
            throw new Exception('Days must be greater than zero');
        }
    }

    public function handle(): DateTime
    {
        $modify = $this->days . " days";

        if ($this->days % 30 == 0) {
            $modify = $this->days / 30 . " month";
        }

        if ($this->days % 365 == 0) {
            $modify = $this->days / 365 . " year";
        }

        return $this->date->modify("+{$modify}");
    }
}
