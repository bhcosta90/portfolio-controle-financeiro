<?php

namespace Modules\Cobranca\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Cobranca\Models\FormaPagamento;

final class FormaPagamentoService
{
    public function __construct(private FormaPagamento $repository)
    {
        //
    }

    public function data()
    {
        return $this->repository->orderBy('ordem')->orderBy('nome');
    }

    public function webStore($data)
    {
        return $this->repository->create($data);
    }

    public function find($id)
    {
        return $this->repository->where('uuid', $id)->first();
    }

    public function getById($id)
    {
        return $this->repository->find($id);
    }

    public function webUpdate($data, $id)
    {
        $obj = $this->repository->find($id);
        $obj->update($data);
        return $obj;
    }

    public function delete($id)
    {
        return $this->repository->where('id', $id)->delete();
    }

    public function pluck()
    {
        return $this->data()->where('ativo', 1)->get()->pluck('nome', 'uuid')->toArray();
    }

    public function registrDefault($obj)
    {
        if (Schema::hasTable('forma_pagamentos')) {

            $formapagamentos = [
                ['nome' => 'Dinheiro'],
                ['nome' => 'Cartão de Crédito'],
                ['nome' => 'Cartão de Débito'],
                ['nome' => 'Transferência'],
                ['nome' => 'Boleto'],
            ];

            foreach ($formapagamentos as $i => $forma) {
                $forma += [
                    'uuid' => (string) str()->uuid(),
                    'tenant_id' => (string) $obj->tenant_id,
                    'ativo' => true,
                    'ordem' => $i * 5
                ];

                DB::table('forma_pagamentos')->insert($forma);
            }
        }
    }
}
