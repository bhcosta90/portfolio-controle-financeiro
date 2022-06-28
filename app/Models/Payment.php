<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, Traits\TenantTrait, Traits\UuidTrait, SoftDeletes;

    public $fillable = [
        'id',
        'tenant_id',
        'entity_id',
        'entity_type',
        'account_from_id',
        'account_to_id',
        'value',
        'status',
        'date',
    ];
}
