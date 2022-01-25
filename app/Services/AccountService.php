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

    public function getDataIndex(array $filters = [])
    {
        return $this->repository->where('user_id', $filters['user_id']);
    }

    public function pluck($idUser)
    {
        return $this->repository->orderBy('name')->where('user_id', $idUser)->pluck('name', 'uuid')->toArray();
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

    public function myTotal(int $userId)
    {
        return $this->repository->where('user_id', $userId)->sum('value');
    }
}
