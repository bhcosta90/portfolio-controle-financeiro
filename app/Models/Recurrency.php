<?php

namespace App\Models;

use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Recurrency.
 *
 * @package namespace App\Models;
 */
class Recurrency extends Model implements Transformable
{
    use TransformableTrait, UuidGenerate, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'type', 'can_updated', 'user_id'];

    public function getNameAttribute($value)
    {
        return __($value);
    }

}
