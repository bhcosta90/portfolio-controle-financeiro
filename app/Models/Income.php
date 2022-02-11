<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Income.
 *
 * @package namespace App\Models;
 */
class Income extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public function charge()
    {
        return $this->morphOne(Charge::class, 'chargeable');
    }

    public function chargeable()
    {
        return $this->morphTo();
    }

    public function parcels()
    {
        return $this->hasMany(Charge::class, 'basecharge_id')
            ->where('basecharge_type', self::class)
            ->where('chargeable_type', '!=', self::class)
            ->orderBy('id', 'asc');
    }

    public function parcelsActive()
    {
        return $this->parcels()->where('status', Charge::$STATUS_PENDING)
            ->whereNull('deleted_at');
    }
}
