<?php

namespace App\Services;

use App\Repositories\AccountRepositoryEloquent as Eloquent;
use App\Repositories\Contracts\AccountRepository as Contract;
use Exception;
use Illuminate\Support\Facades\DB;

class AccountService
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

    public function pluck()
    {
        return $this->repository->orderBy('name')->pluck('name', 'uuid')->toArray();
    }

    public function updateValue($id, $value)
    {
        return $this->repository->updateValue($id, $value);
    }

    public function getBy($uuid)
    {
        return $this->repository->where('uuid', $uuid)->first();
    }

    public function webUpdate($id, $data)
    {
        return $this->repository->update($data, $id);
    }

    public function webStore($data)
    {
        return $this->repository->create($data);
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }
}
