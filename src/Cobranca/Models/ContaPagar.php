<?php

namespace Modules\Cobranca\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContaPagar extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $table = 'conta_pagar';

    public function cobranca()
    {
        return $this->morphOne(Cobranca::class, 'cobranca');
    }
}
