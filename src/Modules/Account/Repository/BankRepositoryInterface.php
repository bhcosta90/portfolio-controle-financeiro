<?php

namespace Costa\Modules\Account\Repository;

use Costa\Shareds\Contracts\RepositoryInterface;

interface BankRepositoryInterface extends RepositoryInterface
{
    public function pluck(): array;

    public function total(): float;
}
