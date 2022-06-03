<?php

namespace Costa\Modules\Bank\Repository;

use Costa\Shared\Contracts\RepositoryInterface;

interface BankRepositoryInterface extends RepositoryInterface
{
    public function total(): float;
}
