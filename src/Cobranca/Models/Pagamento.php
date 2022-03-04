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

    public static $PAGAMENTO_TIPO_RECEITA = [ContaReceber::class];
    public static $PAGAMENTO_TIPO_DESPESA = [ContaPagar::class];

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
        // $nomeCliente = $this->entidade ? $this->entidade?->nome . ' - ' : '';
        // $movimento = $this->movimento;
        // $parcela = $this->parcela ? " Parcela: {$this->parcela}" : "";

        // $parentes = ['(', ')'];
        // if (empty($this->parcela) && empty($this->entidade)) {
        //     $parentes = [null, null];
        // }

        // return "{$movimento} <small>{$parentes[0]}{$parcela}{$nomeCliente}{$parentes[1]}</small>";

        $movimento = $this->movimento;

        $incremento = "";

        if ($this->entidade) {
            $incremento .= $this->entidade->nome . ' - ';
        }

        $incremento = substr($incremento, 0, -3);

        if($incremento){
            $incremento = " <small>({$incremento})</small>";
        }

        return $movimento . $incremento;
    }
}
