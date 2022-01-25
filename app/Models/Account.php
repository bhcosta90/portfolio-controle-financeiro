<?php

namespace App\Models;

use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * Class Account.
 *
 * @package namespace App\Models;
 */
class Account extends Model implements Transformable
{
    use TransformableTrait, UuidGenerate, HasFactory, SoftDeletes, RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = true;
    protected $historyLimit = 500;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'value',
        'bank_code',
        'bank_account',
        'bank_digit',
        'bank_agency',
    ];

    protected $casts = [
        'value' => 'float',
    ];

}
