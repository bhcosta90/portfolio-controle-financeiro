<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\CostRepository;
use App\Models\Cost;
use App\Validators\CostValidator;

/**
 * Class CostRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CostRepositoryEloquent extends BaseRepository implements CostRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Cost::class;
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
