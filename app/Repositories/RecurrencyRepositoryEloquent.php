<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\RecurrencyRepository;
use App\Models\Recurrency;
use App\Validators\RecurrencyValidator;

/**
 * Class RecurrencyRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class RecurrencyRepositoryEloquent extends BaseRepository implements RecurrencyRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Recurrency::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
