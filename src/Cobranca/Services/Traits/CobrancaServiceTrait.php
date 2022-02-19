<?php

namespace Modules\Cobranca\Services\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Cobranca\Models\Cobranca;
use Modules\Cobranca\Services\CobrancaService;
use Modules\Cobranca\Services\ContaBancariaService;
use Modules\Cobranca\Services\FormaPagamentoService;
use Modules\Cobranca\Services\FrequenciaService;
use Modules\Entidade\Services\EntidadeService;

trait CobrancaServiceTrait
{

    protected abstract function table(): string;

    protected abstract function model(): Model;

    public function data($filter = [])
    {
        return $this->repository->with([
            'cobranca.entidade',
            'cobranca.frequencia',
            'cobranca.conta_bancaria'
        ])->join('cobrancas', function ($q) {
            $q->on('cobrancas.cobranca_id', '=', $this->table() . '.id')
                ->where('cobrancas.cobranca_type', get_class($this->model()));
        })
            ->where(fn ($query) => ($like = $filter['descricao'] ?? null) ? $query->where('cobrancas.descricao', 'like', "%{$like}%") : null)
            ->where(function ($query) use ($filter) {
                $query->where(function ($query) use ($filter) {
                    $query->where(fn ($query) => $filter['data_inicio'] ?? null ? $query->where('cobrancas.data_vencimento', '>=', $filter['data_inicio']) : null)
                        ->where(fn ($query) => $filter['data_final'] ?? null ? $query->where('cobrancas.data_vencimento', '<=', $filter['data_final']) : null);
                })->orWhere(fn ($query) => in_array($filter['type'] ?? null, [0, 2, 5]) && !empty($filter['data_inicio']) ? $query->where('cobrancas.data_vencimento', '<', $filter['data_inicio']) : null);
            })
            ->where(fn ($query) => empty($filter['entidade_id']) ? null : $query->whereHas('cobranca.entidade', fn ($query) => $query->where('uuid', $filter['entidade_id'])))
            ->where(function ($query) use ($filter) {
                switch ($filter['type'] ?? 0) {
                    case 0:
                        $query->where('status', '!=', [Cobranca::$STATUS_PAGO]);
                        break;
                    case 1:
                        $query->whereIn('status', [Cobranca::$STATUS_PENDENTE]);
                        break;
                    case 2:
                    case 3:
                        $query->whereIn('status', [Cobranca::$STATUS_PENDENTE, Cobranca::$STATUS_PAGO]);
                        break;
                    case 4:
                        $query->whereIn('status', [Cobranca::$STATUS_PAGO]);
                        break;
                    case 5:
                        $query->where('cobrancas.data_vencimento', '<=', Carbon::now()->format('Y-m-d'));
                        break;
                }
            })
            ->whereNull('cobrancas.deleted_at')
            ->where('cobrancas.tenant_id', tenant('id'))
            ->select($this->table() . '.*')
            ->orderBy('cobrancas.status')
            ->orderBy('cobrancas.data_vencimento')
            ->orderBy('cobrancas.id', 'desc');
    }

    public function total($filter)
    {
        $valor = $this->data($filter)->sum('cobrancas.valor_cobranca');

        return [
            'valor' => $valor,
            'formatado' => str()->numberEnToBr($valor),
        ];
    }

    public function webStore($data)
    {
        $idEntidade = $this->getEntidadeService()->find($data['entidade_id'])?->id;
        $data['frequencia_id'] = $this->getFrequenciaService()->find($data['frequencia_id'])?->id;
        $data['conta_bancaria_id'] = $this->getContaBancariaService()->find($data['conta_bancaria_id'])?->id;
        $data['forma_pagamento_id'] = $this->getFormaPagamentoService()->find($data['forma_pagamento_id'])->id;

        $dataErros = [];
        if ($data['entidade_id'] && empty($idEntidade)) {
            $dataErros += [
                'fornecedor' => 'Fornecedor não existe em nossa base de dados',
                'cliente' => 'Cliente não existe em nossa base de dados',
            ];
        }

        if (empty($data['descricao']) && empty($idEntidade)) {
            $dataErros += [
                'descricao' => 'Caso não informado o fornecedor, a descrição é obrigatória'
            ];
        }

        if ($dataErros) {
            throw ValidationException::withMessages($dataErros);
        }

        $data['valor_cobranca'] = $data['valor_cobranca'];
        $data['entidade_id'] = $idEntidade;

        return DB::transaction(function () use ($data) {
            return $this->getCobrancaService()->store($this->model(), $data);
        });
    }

    /**
     * @return EntidadeService
     */
    protected function getEntidadeService()
    {
        return app(EntidadeService::class);
    }

    /**
     * @return CobrancaService
     */
    protected function getCobrancaService()
    {
        return app(CobrancaService::class);
    }

    /**
     * @return FrequenciaService
     */
    protected function getFrequenciaService()
    {
        return app(FrequenciaService::class);
    }

    /**
     * @return ContaBancariaService
     */
    protected function getContaBancariaService()
    {
        return app(ContaBancariaService::class);
    }

    /**
     * @return FormaPagamentoService
     */
    protected function getFormaPagamentoService()
    {
        return app(FormaPagamentoService::class);
    }
}
