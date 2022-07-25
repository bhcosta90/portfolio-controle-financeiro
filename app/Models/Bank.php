<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use HasFactory, Traits\UuidTrait, SoftDeletes, Traits\TenantTrait;

    public $fillable = [
        'id',
        'tenant_id',
        'name',
        'code',
        'account',
        'account_digit',
        'agency',
        'agency_digit',
        'active',
    ];
}
