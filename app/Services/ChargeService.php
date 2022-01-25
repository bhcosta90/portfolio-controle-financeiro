<?php

namespace App\Services;

use App\Repositories\ChargeRepositoryEloquent as Eloquent;
use App\Repositories\Contracts\ChargeRepository as Contract;
use Exception;

class ChargeService
{
    private Contract $repository;

    public function __construct(Contract $repository)
    {
        /** @var Eloquent */
        $this->repository = $repository;
    }

    public function getDataIndex()
    {
        return $this->repository;
    }

    public function getBy($uuid)
    {
        return $this->repository->where('uuid', $uuid)->first();
    }

    public function webUpdate($id, $data)
    {
        return $this->repository->update($data, $id);
    }
}
