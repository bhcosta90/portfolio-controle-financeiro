<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes, Traits\TenantTrait, Traits\UuidTrait;

    public $fillable = [
        'id',
        'tenant_id',
        'value',
        'value_bank',
        'status',
        'type',
        'relationship_id',
        'relationship_type',
        'charge_id',
        'charge_type',
        'account_bank_id',
        'date',
        'title',
        'resume',
        'relationship_name',
    ];
}
