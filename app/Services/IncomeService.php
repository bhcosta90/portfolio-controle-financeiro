<?php

namespace App\Services;

use App\Repositories\Contracts\IncomeRepository as Contract;
use App\Repositories\IncomeRepositoryEloquent as Eloquent;
use Exception;

class IncomeService extends BaseCostIncomeService
{
    protected Contract $repository;

    public function __construct(Contract $repository)
    {
        /** @var Eloquent */
        $this->repository = $repository;
    }
}
