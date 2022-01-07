<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\IncomeRepository;
use App\Models\Income;
use App\Validators\IncomeValidator;

/**
 * Class IncomeRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class IncomeRepositoryEloquent extends BaseRepository implements IncomeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Income::class;
    }



    public function createWithCharge(array $data)
    {
        $obj = $this->create([]);

        $objCharge = (new \App\Models\Charge)->fill($data);
        $objCharge->chargeable_id = $obj->id;
        $objCharge->chargeable_type = get_class($obj);
        $objCharge->save();

        return $obj;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}