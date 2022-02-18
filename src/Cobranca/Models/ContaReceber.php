<?php

namespace Modules\Cobranca\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContaReceber extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $table = 'conta_receber';

    public function cobranca()
    {
        return $this->morphOne(Cobranca::class, 'cobranca');
    }

}
