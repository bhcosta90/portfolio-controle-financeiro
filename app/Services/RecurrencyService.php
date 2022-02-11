<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\RecurrencyRepository as Contract;
use App\Repositories\RecurrencyRepositoryEloquent as Eloquent;
use Costa\LaravelPackage\Traits\Support\UserTrait;

class RecurrencyService
{
    private Contract $repository;

    public function __construct(Contract $repository)
    {
        /** @var Eloquent */
        $this->repository = $repository;
    }

    public function data($filter)
    {
        return $this->repository->where('user_id', $filter['user_id'])
            ->orderBy('name');
    }

    public function find($id){
        return $this->repository->where('uuid', $id)->first();
    }

    public function getById($id)
    {
        return $this->repository->find($id);
    }

    public function webStore($data)
    {
        $data['type'] = "{$data['days']}|day";
        return $this->repository->create($data);
    }

    public function webUpdate($data, $id)
    {
        $data['type'] = "{$data['days']}|day";

        return $this->repository->update($data, $id);
    }

    public function register(User $obj)
    {
        $data = [
            'month' => 'Month',
            'twoweek' => 'Two week',
            'week' => 'Week',
            'fifth_business_day' => 'Fifth business day',
            'every_20th' => 'Every 20th',
            'every_last_day' => 'Every last day',
        ];

        foreach ($data as $k => $rs) {
            $this->repository->create([
                'user_id' => $obj->id,
                'type' => $k,
                'name' => $rs,
                'can_updated' => false,
            ]);
        }
    }

    public function pluck($idUser)
    {
        return $this->data(['user_id' => $idUser])->pluck('name', 'id')->toArray();
    }
}
