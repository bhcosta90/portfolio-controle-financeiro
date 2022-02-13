<?php

namespace App\Services;

use App\Repositories\Contracts\ParcelRepository as Contract;
use App\Repositories\ParcelRepositoryEloquent as Eloquent;
use Costa\LaravelPackage\Traits\Support\UserTrait;
use Illuminate\Support\Collection;

class ParcelService
{
    use UserTrait;

    private Contract $repository;

    public function __construct(Contract $repository)
    {
        /** @var Eloquent */
        $this->repository = $repository;
    }

    public function data()
    {
        return $this->repository;
    }

    public function store($objBase, array $data, Collection $parcels){
        foreach ($parcels as $i => $parcel) {

            $obj = $this->repository->create([]);

            $this->getChargeService()->create($obj, [
                'basecharge_type' => get_class($objBase),
                'basecharge_id' => $objBase->id,
                'user_id' => $data['user_id'],
                'resume' => 'Parcel :actual',
                'parcel_actual' => $i + 1,
                'parcel_total' => count($parcel) + 1,
                'value' => $parcel['value'],
                'customer_name' => $data['name'],
                'due_date' => $parcel['due_date'],
                'value_recurrency' => $parcel['value'],
            ]);
        }
    }

    /**
     * @return ChargeService
     */
    public function getChargeService()
    {
        return app(ChargeService::class);
    }
}