<?php

namespace App\Services;

use App\Repositories\AccountRepositoryEloquent as Eloquent;
use App\Repositories\Contracts\AccountRepository as Contract;
use Exception;
use Illuminate\Http\Response;
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

    public function pluck($idUser, array $types = null)
    {
        return $this->repository
            ->orderBy('name')
            ->where('user_id', $idUser)
            ->where('can_deleted', true)
            ->pluck('name', 'uuid')->toArray();
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
        $obj = $this->repository->find($id);
        if ($obj->can_deleted == false) {
            throw new Exception(__('This bank account cannot be removed'), Response::HTTP_BAD_REQUEST);
        }

        return $this->repository->delete($id);
    }

    public function myTotal(int $userId)
    {
        return $this->repository->where('user_id', $userId)->sum('value');
    }
}
