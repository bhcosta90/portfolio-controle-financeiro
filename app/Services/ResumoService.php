<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Cobranca\Models\Cobranca;
use Modules\Cobranca\Models\ContaPagar;
use Modules\Cobranca\Models\ContaReceber;
use Modules\Cobranca\Models\Pagamento;

class ResumoService
{
    public function contapagarhojequantidade()
    {
        $ret = DB::table('cobrancas')
            ->where('tenant_id', tenant('id'))
            ->where('data_vencimento', Carbon::now()->format('Y-m-d'))
            ->whereIn('cobranca_type', [ContaPagar::class])
            ->where('status', Cobranca::$STATUS_PENDENTE)
            ->whereNull('deleted_at')
            ->count();

        return [
            'quantidade' => $ret,
        ];
    }

    public function contapagarvencidasquantidade()
    {
        $ret = DB::table('cobrancas')
            ->where('tenant_id', tenant('id'))
            ->where('data_vencimento', '<', Carbon::now()->format('Y-m-d'))
            ->whereIn('cobranca_type', [ContaPagar::class])
            ->where('status', Cobranca::$STATUS_PENDENTE)
            ->whereNull('deleted_at')
            ->count();

        return [
            'quantidade' => $ret,
        ];
    }

    public function contareceberhojequantidade()
    {
        $ret = DB::table('cobrancas')
            ->where('tenant_id', tenant('id'))
            ->where('data_vencimento', Carbon::now()->format('Y-m-d'))
            ->whereIn('cobranca_type', [ContaReceber::class])
            ->where('status', Cobranca::$STATUS_PENDENTE)
            ->whereNull('deleted_at')
            ->count();

        return [
            'quantidade' => $ret,
        ];
    }

    public function contarecebervencidasquantidade()
    {
        $ret = DB::table('cobrancas')
            ->where('tenant_id', tenant('id'))
            ->where('data_vencimento', '<', Carbon::now()->format('Y-m-d'))
            ->whereIn('cobranca_type', [ContaReceber::class])
            ->where('status', Cobranca::$STATUS_PENDENTE)
            ->whereNull('deleted_at')
            ->count();

        return [
            'quantidade' => $ret,
        ];
    }

    public function lucro()
    {
        $quantoRecebi = DB::table('pagamentos')
            ->where('tenant_id', tenant('id'))
            ->whereIn('pagamento_type', [Pagamento::$PAGAMENTO_TIPO_RECEITA])
            ->sum('valor_total');

        $quantoPaguei = DB::table('pagamentos')
            ->where('tenant_id', tenant('id'))
            ->whereIn('pagamento_type', [Pagamento::$PAGAMENTO_TIPO_DESPESA])
            ->sum('valor_total');

        $valor = $quantoRecebi - $quantoPaguei;

        return [
            'valor_real' => $valor,
            'valor_formatado' => str()->numberEnToBr($valor),
        ];
    }

    public function contapagar()
    {
        $total =
            DB::table('cobrancas')
            ->where('tenant_id', tenant('id'))
            ->whereBetween('data_vencimento', [Carbon::now()->firstOfMonth()->format('Y-m-d'), Carbon::now()->endOfMonth()->format('Y-m-d')])
            ->whereIn('cobranca_type', [ContaPagar::class])
            ->where('status', Cobranca::$STATUS_PENDENTE)
            ->whereNull('deleted_at')
            ->count();

        return [
            'valor_real' => $this->getValorTotalPagar(),
            'valor_formatado' => str()->numberEnToBr($this->getValorTotalPagar()),
            'quantidade' => $total,
        ];
    }

    public function contareceber()
    {
        $total =
            DB::table('cobrancas')
            ->where('tenant_id', tenant('id'))
            ->whereBetween('data_vencimento', [Carbon::now()->firstOfMonth()->format('Y-m-d'), Carbon::now()->endOfMonth()->format('Y-m-d')])
            ->whereIn('cobranca_type', [ContaReceber::class])
            ->where('status', Cobranca::$STATUS_PENDENTE)
            ->whereNull('deleted_at')
            ->count();
        return [
            'quantidade' => $total,
            'valor_real' => $this->getValorTotalReceber(),
            'valor_formatado' => str()->numberEnToBr($this->getValorTotalReceber()),
        ];
    }

    public function saldobancario()
    {
        $valor = $this->getValorTotalReceber() - $this->getValorTotalPagar() + $this->getValorTotalBancos();

        return [
            'valor_real' => $valor ?? 0,
            'valor_formatado' => str()->numberEnToBr($valor ?? 0),
        ];
    }

    private function getValorTotalReceber()
    {
        return DB::table('cobrancas')
            ->where('tenant_id', tenant('id'))
            ->whereBetween('data_vencimento', [Carbon::now()->firstOfMonth()->format('Y-m-d'), Carbon::now()->endOfMonth()->format('Y-m-d')])
            ->whereIn('cobranca_type', [ContaReceber::class])
            ->where('status', Cobranca::$STATUS_PENDENTE)
            ->whereNull('deleted_at')
            ->sum('valor_cobranca');
    }

    private function getValorTotalPagar()
    {
        return DB::table('cobrancas')
            ->where('tenant_id', tenant('id'))
            ->whereBetween('data_vencimento', [Carbon::now()->firstOfMonth()->format('Y-m-d'), Carbon::now()->endOfMonth()->format('Y-m-d')])
            ->whereIn('cobranca_type', [ContaPagar::class])
            ->where('status', Cobranca::$STATUS_PENDENTE)
            ->whereNull('deleted_at')
            ->sum('valor_cobranca');
    }

    private function getValorTotalBancos()
    {
        return DB::table('conta_bancarias')
            ->where('tenant_id', tenant('id'))
            ->where('ativo', 1)
            ->sum('valor');
    }
}
