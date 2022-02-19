<?php

namespace Modules\Cobranca\Models;

use App\Models\User;
use Costa\LaravelPackage\Traits\Models\UuidGenerate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Entidade\Models\Banco;
use Modules\Entidade\Models\Entidade;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Pagamento extends Model
{
    use HasFactory, UuidGenerate, BelongsToTenant;

    protected $fillable = [
        'cobranca_id',
        'user_id',
        'conta_bancaria_id',
        'forma_pagamento_id',
        'entidade_id',
        'pagamento_type',
        'movimento',
        'descricao',
        'parcela',
        'tipo',
        'valor_cobranca',
        'valor_multa',
        'valor_juros',
        'valor_desconto',
        'valor_total',
        'saldo_anterior',
        'saldo_atual',
    ];

    public static $TIPO_PAGAMENTO = 'PA';
    public static $TIPO_RECEBIMENTO = 'RE';

    public static $PAGAMENTO_TIPO_RECEITA = [ContaReceber::class];
    public static $PAGAMENTO_TIPO_DESPESA = [ContaPagar::class];

    public static function getTipoFormatarAttribute($status = null)
    {
        $ret = [
            self::$TIPO_PAGAMENTO => 'Pagamento',
            self::$TIPO_RECEBIMENTO => 'Recebimento',
        ];

        if (!empty($status)) {
            return $ret[$status] ?? null;
        }

        return $ret;
    }

    public function conta_bancaria()
    {
        return $this->belongsTo(ContaBancaria::class);
    }

    public function forma_pagamento()
    {
        return $this->belongsTo(FormaPagamento::class);
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }

    public function getFormatacaoMovimentoAttribute()
    {
        $nomeCliente = $this->entidade->nome;
        $movimento = $this->movimento;
        $parcela = $this->parcela ? " Parcela: {$this->parcela} - " : "";

        return "{$movimento} <small>({$parcela}{$nomeCliente})</small>";
    }
}
