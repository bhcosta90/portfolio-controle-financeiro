<?php

namespace App\Services;

use App\Models\Charge;
use App\Models\Cost;
use App\Models\Income;
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

    public function data()
    {
        return $this->repository;
    }

    public function registerExtract($obj, $value, $type) {
        $data = [
            'extract_type' => get_class($obj),
            'extract_id' => $obj->id,
            'type' => $type,
            'user_id' => $this->getUser()->id,
        ];

        switch ($typeClass = get_class($obj)) {
            case Income::class:
                $this->getUser()->increment('balance_value', $value);
                break;
            case Cost::class:
                $this->getUser()->decrement('balance_value', $value);
                $value *= -1;
                break;
            case Charge::class:
                return;
                break;
            default:
                throw new Exception("`{$typeClass}` do not configured in extract");
        }

        $data += [
            'value' => $value,
        ];

        return $this->repository->create($data);

    }
}
