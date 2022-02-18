<?php

namespace Modules\Entidade\Models;

use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Entidade extends Model
{
    use HasFactory, UuidGenerate, SoftDeletes, BelongsToTenant;

    public static $TIPO_PF = 'PF';
    public static $TIPO_PJ = 'PJ';
    public static $TIPO_EXTERIOR = 'EX';

    protected $fillable = [
        'entidade_type',
        'entidade_id',
        'nome',
        'tipo',
        'email',
        'telefone',
        'endereco',
        'observacao',
        'banco_id',
        'banco_codigo',
        'documento',
        'banco_agencia',
        'banco_conta',
        'ativo',
    ];

    public function entidade()
    {
        return $this->morphTo();
    }

    public static function getTipoAttribute($tipo = null)
    {
        $tipos = [
            self::$TIPO_PF => 'Pessoa Física',
            self::$TIPO_PJ => 'Pessoa Jurídica',
            self::$TIPO_EXTERIOR => 'Estrangeiro',
        ];

        if (!empty($tipo)) {
            return $tipos[$tipo];
        }

        return $tipos;
    }
}
