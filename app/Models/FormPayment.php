<?php

namespace App\Models;

use App\FormPayment\SimpleFormPayment;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class FormPayment.
 *
 * @package namespace App\Models;
 */
class FormPayment extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    const TYPES_FORM_PAYMENT = [
        SimpleFormPayment::class,
    ];
}
