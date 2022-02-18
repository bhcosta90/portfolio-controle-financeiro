<?php

namespace Modules\Cobranca\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Cobranca\Models\Cobranca;
use Modules\Entidade\Services\EntidadeService;

final class CobrancaService
{
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
                $data['valor_cobranca'] = str()->numberBrToEn($rs['valor']);
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

        $data['valor_cobranca'] = str()->numberBrToEn($data['valor_cobranca']);
        $data['entidade_id'] = $idEntidade;

        $obj = $this->getById($id);

        $objBase = clone $obj;
        if (count($data['parcelas'] ?? []) > 1) {

            $ret = DB::transaction(function () use ($data, $obj, $objBase) {
                $ret = [];
                foreach ($data['parcelas'] as $parcel => $rs) {
                    $data['valor_cobranca'] = str()->numberBrToEn($rs['valor']);
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
        return $this->getById($id)->delete();
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
