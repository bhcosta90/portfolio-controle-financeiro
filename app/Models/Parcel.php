<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Parcel.
 *
 * @package namespace App\Models;
 */
class Parcel extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'charge_type',
        'charge_id',
    ];

    public function charge()
    {
        return $this->hasOne(Charge::class, 'chargeable_id', 'id')
            ->orderBy('parcel_actual', 'desc')
            ->where('chargeable_type', self::class);
    }

    public function chargeParcel()
    {
        return $this->hasOne(Charge::class, 'chargeable_id', 'charge_id')->where('chargeable_type', $this->charge_type);
    }

}
