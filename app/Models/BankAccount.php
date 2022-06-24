<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory, Traits\TenantTrait, Traits\UuidTrait, SoftDeletes;

    public $fillable = ['id', 'name'];
}
