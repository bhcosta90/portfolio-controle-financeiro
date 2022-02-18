<?php

namespace Modules\Cobranca\Models;

use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class FormaPagamento extends Model
{
    use HasFactory, UuidGenerate, BelongsToTenant;

    protected $fillable = [
        'nome',
        'ativo',
        'ordem',
    ];
}
