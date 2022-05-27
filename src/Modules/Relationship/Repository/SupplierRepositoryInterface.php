<?php

namespace Costa\Modules\Relationship\Repository;

use Costa\Shareds\Contracts\RepositoryInterface;

interface SupplierRepositoryInterface extends RepositoryInterface
{
    public function pluck(): array;
}
