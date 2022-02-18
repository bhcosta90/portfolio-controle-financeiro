<?php

namespace Modules\Cobranca\Models;

use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Frequencia extends Model
{
    use HasFactory, SoftDeletes, UuidGenerate, BelongsToTenant;

    protected $fillable = [
        'tipo',
        'nome',
        'ativo',
        'ordem_frequencia',
        'ordem_parcela',
    ];
}
