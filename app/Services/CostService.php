<?php

namespace App\Services;

use App\Models\Cost;
use App\Repositories\Contracts\CostRepository as Contract;
use App\Repositories\CostRepositoryEloquent as Eloquent;

class CostService extends Abstracts\ChargeAbstractService
{
    protected Contract $repository;

    public function __construct(Contract $repository)
    {
        /** @var Eloquent */
        $this->repository = $repository;
    }

    protected function tableName(): string
    {
        return 'costs';
    }

    protected function modelName(): string
    {
        return Cost::class;
    }
}
