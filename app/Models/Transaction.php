<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, Traits\UuidTrait, SoftDeletes, Traits\TenantTrait;

    public $fillable = [
        'id',
        'group_id',
        'tenant_id',
        'account_to_id',
        'account_from_id',
        'entity_id',
        'entity_type',
        'relationship_id',
        'relationship_type',
        'relationship_name',
        'title',
        'status',
        'value',
        'previous_value',
        'type',
        'date',
    ];
}
