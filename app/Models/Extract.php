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
        'value',
        'type',
        'user_id',
    ];

    public static function getTypeOptionsAttribute($type = null)
    {
        $ret = [
            self::$TYPE_PAYMENT => 'Pagamento',
        ];

        if (!empty($type)) {
            return $ret[$type] ?? null;
        }

        return $ret;
    }

}
