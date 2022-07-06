<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountBank extends Model
{
    use HasFactory, SoftDeletes, Traits\TenantTrait, Traits\UuidTrait;

    public $fillable = [
        'id',
        'entity',
        'name',
        'value',
        'bank_code',
        'bank_agency',
        'bank_agency_digit',
        'bank_account',
        'bank_account_digit',
    ];
}
