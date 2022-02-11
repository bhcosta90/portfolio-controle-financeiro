<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\ExtractRepository;
use App\Models\Extract;
use App\Validators\ExtractValidator;

/**
 * Class ExtractRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ExtractRepositoryEloquent extends BaseRepository implements ExtractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Extract::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
