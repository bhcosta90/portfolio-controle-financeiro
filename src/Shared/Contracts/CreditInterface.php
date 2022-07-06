<?php

namespace Core\Shared\Contracts;

interface CreditInterface
{
    public function addCredit(float $value);

    public function removeCredit(float $value);
}
