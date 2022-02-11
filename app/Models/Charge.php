<?php

namespace App\Models;

use Carbon\Carbon;
use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Charge.
 *
 * @package namespace App\Models;
 */
class Charge extends Model implements Transformable
{
    use TransformableTrait, UuidGenerate;

    public static $STATUS_PENDING = 'PE';
    public static $STATUS_PAYED = 'PA';
    public static $STATUS_CANCELED = 'CA';
    public static $STATUS_SYNCRONIZING = 'SY';

    public static $TYPE_PAYMENT_1X = -1;
    public static $TYPE_PAYMENT_PARCEL = -2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'recurrency_id',
        'resume',
        'description',
        'chargeable_id',
        'chargeable_type',
        'basecharge_id',
        'basecharge_type',
        'value',
        'value_pay',
        'value_recurrency',
        'customer_name',
        'due_date',
        'date_start',
        'date_end',
        'status',
        'parcel_actual',
        'parcel_total',
    ];

    public static function getTypeOptionsAttribute($type = null)
    {
        $ret = [
            self::$TYPE_PAYMENT_1X => 'Pagamento a vista',
            self::$TYPE_PAYMENT_PARCEL => 'Parcelar cobrança',
        ];

        if (!empty($type)) {
            return $ret[$type] ?? null;
        }

        return $ret;
    }

    public static function getStatusOptionsAttribute($status = null)
    {
        $ret = [
            self::$STATUS_PENDING => 'Pending',
            self::$STATUS_PAYED => 'Payed',
            self::$STATUS_CANCELED => 'Canceled',
            self::$STATUS_SYNCRONIZING => 'In syncronyzed',
        ];

        if (!empty($status)) {
            return $ret[$status] ?? null;
        }

        return $ret;
    }

    public function chargeable()
    {
        return $this->morphTo();
    }

    public function basecharge()
    {
        return $this->morphTo();
    }

    public function chargeParcel()
    {
        return $this->hasOne(Charge::class);
    }

    public function getIsDueAttribute()
    {
        return (new Carbon())->format('Y-m-d') > $this->due_date;
    }

    public function recurrency()
    {
        return $this->belongsTo(Recurrency::class);
    }
}
