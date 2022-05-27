<?php

namespace Costa\Modules\Relationship\Repository;

use Costa\Shareds\Contracts\RepositoryInterface;

interface CustomerRepositoryInterface extends RepositoryInterface
{
    public function pluck(): array;
}
