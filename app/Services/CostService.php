<?php

namespace App\Services;

use App\Repositories\Contracts\CostRepository as Contract;
use App\Repositories\CostRepositoryEloquent as Eloquent;

class CostService extends BaseCostIncomeService
{
    protected Contract $repository;

    public function __construct(Contract $repository)
    {
        /** @var Eloquent */
        $this->repository = $repository;
    }
}
