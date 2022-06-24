<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recurrence extends Model
{
    use HasFactory, Traits\UuidTrait, Traits\TenantTrait;

    public $fillable = ['id', 'name', 'days'];
}
