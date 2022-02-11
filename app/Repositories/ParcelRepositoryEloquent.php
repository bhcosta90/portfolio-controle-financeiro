<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\ParcelRepository;
use App\Models\Parcel;
use App\Validators\ParcelValidator;

/**
 * Class ParcelRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ParcelRepositoryEloquent extends BaseRepository implements ParcelRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Parcel::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
