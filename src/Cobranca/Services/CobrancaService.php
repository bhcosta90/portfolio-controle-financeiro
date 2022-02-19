<?php

namespace Modules\Cobranca\Services;

use Carbon\Carbon;
use Costa\LaravelPackage\Traits\Support\DayWeekTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Cobranca\Models\Cobranca;
use Modules\Entidade\Services\EntidadeService;

final class CobrancaService
{
    use DayWeekTrait;

    public function __construct(private Cobranca $repository)
    {
        //
    }

    public function store(Model $obj, $data)
    {
        $data += [
            'data_original' => $data['data_vencimento'],
        ];

        $ret = [];

        if (count($data['parcelas'] ?? []) > 1) {

            foreach ($data['parcelas'] as $parcel => $rs) {
                $data['valor_cobranca'] = $rs['valor'];
                $data['data_vencimento'] = $rs['data'];
                $data['parcela'] = $parcel + 1;
                $objCreated = $obj->create([]);

                $data['cobranca_id'] = $objCreated->id;
                $data['cobranca_type'] = get_class($objCreated);
                $ret[] = $this->repository->create($data);
            }
        } else {
            $objCreated = $obj->create([]);

            $data += [
                'cobranca_id' => $objCreated->id,
                'cobranca_type' => get_class($objCreated),
            ];
            $ret[] = $this->repository->create($data);
        }

        return $ret;
    }

    public function webUpdate($data, $id)
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

        $obj = $this->getById($id);

        $objBase = clone $obj;
        if (count($data['parcelas'] ?? []) > 1) {

            $ret = DB::transaction(function () use ($data, $obj, $objBase) {
                $ret = [];
                foreach ($data['parcelas'] as $parcel => $rs) {
                    $data['valor_cobranca'] = $rs['valor'];
                    $data['data_vencimento'] = $rs['data'];
                    $data['data_original'] = $rs['data'];
                    $data['parcela'] = $parcel + 1;

                    if (empty($obj)) {
                        $objCreated = $obj ?: app($objBase->cobranca_type)->create([]);
                        $data['cobranca_id'] = $objCreated->id;
                        $data['cobranca_type'] = get_class($objCreated);
                        $ret[] = $this->repository->create($data);
                    } else {
                        $obj->update($data);
                        $ret[] = $obj;
                    }

                    $obj = null;
                }
                return $ret;
            });
        } else {
            $obj = $this->getById($id);
            $obj->update($data);
            $ret = [$obj];
        }

        return $ret;
    }

    public function duplicarCobranca($obj)
    {
        if ($obj->frequencia_id) {
            $repository = app($obj->cobranca_type);
            $objRepository = $repository->create([]);

            $objFrequencia = $this->getFrequenciaService()->getById($obj->frequencia_id);
            $splitTipoFrequencia = collect(explode('|', $objFrequencia->tipo));
            $dataCalculado = (new Carbon($obj->data_vencimento))->addDay($splitTipoFrequencia->first());
            $dataVencimento = $this->onlyDayWeek($dataCalculado, 0, true);

            $ret = $this->repository->fill([
                'cobranca_type' => get_class($objRepository),
                'cobranca_id' => $objRepository->id,
                'valor_cobranca' => $obj->valor_frequencia,
                'valor_frequencia' => $obj->valor_frequencia,
                'data_emissao' => (new Carbon)->format('Y-m-d'),
                'data_original' => $dataCalculado->format('Y-m-d'),
                'data_vencimento' => $dataVencimento->format('Y-m-d'),
                'status' => Cobranca::$STATUS_PENDENTE,
                'valor_original' => null,
            ] + $obj->toArray());

            return $ret->save();
        }
    }

    public function find($id)
    {
        return $this->repository->where('uuid', $id)->first();
    }

    public function getById($id)
    {
        return $this->repository->find($id);
    }

    public function delete($id)
    {
        $obj = $this->getById($id);
        if ($obj->valor_cobranca != $obj->valor_frequencia) {
            $this->duplicarCobranca($obj);
        }
        return $obj->delete();
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
