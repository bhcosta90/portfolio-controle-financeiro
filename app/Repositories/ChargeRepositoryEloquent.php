<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\ChargeRepository;
use App\Models\Charge;
use App\Validators\ChargeValidator;

/**
 * Class ChargeRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ChargeRepositoryEloquent extends BaseRepository implements ChargeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Charge::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
