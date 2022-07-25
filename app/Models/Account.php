<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory, Traits\UuidTrait, Traits\TenantTrait;

    public $fillable = [
        'id',
        'tenant_id',
        'entity_id',
        'entity_type',
        'value',
    ];
}
