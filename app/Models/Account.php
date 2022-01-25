<?php

namespace App\Models;

use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Account.
 *
 * @package namespace App\Models;
 */
class Account extends Model implements Transformable
{
    use TransformableTrait, UuidGenerate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'value',
        'bank_code',
        'bank_account',
        'bank_digit',
    ];

}
