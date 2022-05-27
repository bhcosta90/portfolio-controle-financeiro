<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Relationship extends Model
{
    use HasFactory, Traits\UuidTrait, SoftDeletes;
}
