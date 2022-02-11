<?php

namespace App\Services;

use App\Models\Charge;
use App\Models\Cost;
use App\Models\Income;
use App\Models\Parcel;
use App\Repositories\Contracts\ExtractRepository as Contract;
use App\Repositories\ExtractRepositoryEloquent as Eloquent;
use Costa\LaravelPackage\Traits\Support\UserTrait;
use Exception;

class ExtractService
{
    use UserTrait;

    private Contract $repository;

    public function __construct(Contract $repository)
    {
        /** @var Eloquent */
        $this->repository = $repository;
    }

    public function data($data)
    {
        return $this->repository->where('user_id', $data['user_id'])->orderBy('created_at', 'desc');
    }

    public function registerExtract($obj, $value, $type, $dataExtract = [])
    {
        $data = [
            'extract_type' => get_class($obj),
            'extract_id' => $obj->id,
            'type' => $type,
            'user_id' => $this->getUser()->id,
        ] + $dataExtract;

        switch ($typeClass = get_class($obj)) {
            case Income::class:
                $this->getUser()->increment('balance_value', $value);
                break;
            case Cost::class:
                $this->getUser()->decrement('balance_value', $value);
                $value *= -1;
                break;
            case Parcel::class:
                if ($typeClass == Cost::class) {
                    $value *= -1;
                }
                break;
            default:
                throw new Exception("`{$typeClass}` do not configured in extract");
        }

        $data += [
            'value_transfer' => $value,
        ];

        return $this->repository->create($data);
    }
}
