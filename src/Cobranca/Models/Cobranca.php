<?php

namespace Modules\Cobranca\Models;

use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Entidade\Models\Entidade;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Cobranca extends Model
{
    use HasFactory, SoftDeletes, UuidGenerate, BelongsToTenant;

    protected $fillable = [
        'entidade_id',
        'frequencia_id',
        'conta_bancaria_id',
        'cobranca_type',
        'cobranca_id',
        'descricao',
        'valor_cobranca',
        'valor_original',
        'observacao',
        'data_emissao',
        'data_original',
        'data_vencimento',
        'forma_pagamento_id',
        'parcela',
        'status',
    ];

    public static $STATUS_PENDENTE = '10';
    public static $STATUS_EM_SINCRONIZACAO = '20';
    public static $STATUS_CANCELADO = '30';
    public static $STATUS_PAGO = '40';

    public static function getTextStatusAttribute($status = null)
    {
        $ret = [
            self::$STATUS_PENDENTE => 'Pendente',
            self::$STATUS_PAGO => 'Pago',
            self::$STATUS_CANCELADO => 'Cancelado',
            self::$STATUS_EM_SINCRONIZACAO => 'Em sincronização',
        ];

        if (!empty($status)) {
            return $ret[$status] ?? null;
        }

        return $ret;
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

    public function conta_bancaria()
    {
        return $this->belongsTo(ContaBancaria::class);
    }

    public function frequencia()
    {
        return $this->belongsTo(Frequencia::class);
    }

    public function forma_pagamento()
    {
        return $this->belongsTo(FormaPagamento::class);
    }

    public function cobranca()
    {
        return $this->morphTo();
    }

    public function getDescricaoParcelaAttribute()
    {
        $textoParcela = "<small class='text-muted'>Parcela %s</span>";

        if ($this->descricao && $this->parcela) {
            return sprintf("%s <small class='text-muted'>- Parcela %s</small>", $this->descricao, $this->parcela);
        }

        if (empty($this->descricao) && $this->parcela) {
            return sprintf($textoParcela, $this->parcela);
        }

        return $this->descricao ?: '-';
    }

    public function getValorCobrancaAttribute($str)
    {
        return str()->numberEnToBr($str);
    }

    public function getValorOriginalAttribute($str)
    {
        return $str ? str()->numberEnToBr($str) : null;
    }
}
