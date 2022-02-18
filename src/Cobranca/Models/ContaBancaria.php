<?php

namespace Modules\Cobranca\Models;

use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Entidade\Models\Banco;
use Modules\Entidade\Models\Entidade;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ContaBancaria extends Model
{
    use HasFactory, BelongsToTenant, UuidGenerate, SoftDeletes;

    public static $TIPO_DOCUMENTO_PF = 'PF';
    public static $TIPO_DOCUMENTO_PJ = 'PJ';
    public static $TIPO_DOCUMENTO_EXTERIOR = 'EX';

    public static $TIPO_CC = 'CC';
    public static $TIPO_CP = 'CP';

    protected $fillable = [
        'entidade_id',
        'agencia',
        'conta',
        'tipo',
        'tipo_documento',
        'documento',
        'valor',
        'ativo',
    ];

    public static function getTipoDocumentoAttribute($tipo = null)
    {
        $tipos = [
            self::$TIPO_DOCUMENTO_PF => 'Pessoa Física',
            self::$TIPO_DOCUMENTO_PJ => 'Pessoa Jurídica',
            self::$TIPO_DOCUMENTO_EXTERIOR => 'Estrangeiro',
        ];

        if (!empty($tipo)) {
            return $tipos[$tipo];
        }

        return $tipos;
    }

    public static function getTipoAttribute($tipo = null)
    {
        $tipos = [
            self::$TIPO_CC => 'Conta corrente',
            self::$TIPO_CP => 'Conta poupança',
        ];

        if (!empty($tipo)) {
            return $tipos[$tipo];
        }

        return $tipos;
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

    public function getNomeSelectAttribute()
    {
        return sprintf("%s | Agência: %s | Conta: %s", $this->entidade->nome, $this->agencia, $this->conta);
    }
}
