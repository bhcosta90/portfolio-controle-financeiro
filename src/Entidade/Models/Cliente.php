<?php

namespace Modules\Entidade\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory, Traits\EntidadeTrait;

    protected $fillable = [];
}
