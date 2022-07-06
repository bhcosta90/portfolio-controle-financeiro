<?php

namespace Core\Shared\Contracts;

interface ValueInterface
{
    public function addValue(float $value, string $idPayment);

    public function removeValue(float $value, string $idPayment);
}
