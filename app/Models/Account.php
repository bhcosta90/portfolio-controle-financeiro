<?php

namespace App\Models;

use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Exception;
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

    const TYPE_TRANSFER = 'TRA';
    const TYPE_PAYMENT = 'PAY';

    const TYPES = [
        self::TYPE_TRANSFER,
        self::TYPE_PAYMENT
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'value',
        'type',
        'bank_code',
        'bank_account',
        'bank_digit',
        'bank_agency',
    ];

    protected $casts = [
        'value' => 'float',
        'can_deleted' => 'boolean',
    ];

    public function getRenderedTypeAttribute()
    {
        switch ($this->type) {
            case self::TYPE_TRANSFER:
                return __('Account transfer');
                break;
            case self::TYPE_PAYMENT:
                return __('Account payment');
                break;
            default:
                throw new Exception($this->type . ' do not configured');
        }
    }
}
