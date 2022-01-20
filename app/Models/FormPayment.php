<?php

namespace App\Models;

use App\FormPayment\SimpleFormPayment;
use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class FormPayment.
 *
 * @package namespace App\Models;
 */
class FormPayment extends Model implements Transformable
{
    use TransformableTrait, HasFactory, UuidGenerate, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'user_id',
        'type',
        'sync_data',
        'active',
    ];

    const TYPES_FORM_PAYMENT = [
        SimpleFormPayment::class,
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
