<?php

namespace App\Services;

use App\Repositories\Contracts\FormPaymentRepository as Contract;
use App\Repositories\FormPaymentRepositoryEloquent as Eloquent;

class FormPaymentService
{
    private Contract $repository;

    public function __construct(Contract $repository)
    {
        /** @var Eloquent */
        $this->repository = $repository;
    }

    public function getDataIndex()
    {
        return $this->repository->where('user_id', $this->getUser());
    }

    protected function getUser(): int
    {
        return auth()->user()->id;
    }

    public function actionStore(array $data)
    {
        if ($data['active'] == true) {
            $this->tranformAllInInactive();
        }

        return $this->repository->create($data + ['active' => false]);
    }

    private function tranformAllInInactive($id = null)
    {
        return $this->getDataIndex()->where('active', true)
            ->where(fn ($q) => $id ? $q->where('id', '!=', $id) : null)
            ->update(['active' => false]);
    }
}
