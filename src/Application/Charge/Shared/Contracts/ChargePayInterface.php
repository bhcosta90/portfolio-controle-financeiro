<?php

namespace Core\Application\Charge\Shared\Contracts;

interface ChargePayInterface
{
    public function pay(float $value): float;

    public function cancel(float $value): self;
}
