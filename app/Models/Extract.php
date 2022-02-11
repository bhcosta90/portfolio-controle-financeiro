<?php

namespace App\Models;

use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Extract.
 *
 * @package namespace App\Models;
 */
class Extract extends Model implements Transformable
{
    use TransformableTrait, UuidGenerate;

    public static $TYPE_PAYMENT = 'PA';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'extract_type',
        'extract_id',
        'base_type',
        'base_id',
        'user_id',
        'value_charge',
        'value_transfer',
        'parcel',
        'name',
        'resume',
        'type',
    ];

    public static function getTypeAttribute($type = null)
    {
        $ret = [
            self::$TYPE_PAYMENT => 'Pagamento',
        ];

        if (!empty($type)) {
            return $ret[$type] ?? null;
        }

        return $ret;
    }

    public static function getExtractTypeAttribute($extractType)
    {
        $ret = [
            Income::class => 'Receita',
            Cost::class => 'Despesa',
            Parcel::class => 'Parcela',
        ];

        return $ret[$extractType] ?? $extractType;
    }

    public function extract()
    {
        return $this->hasOne(Charge::class, 'extract_id', 'chargeable_id');
    }


}
