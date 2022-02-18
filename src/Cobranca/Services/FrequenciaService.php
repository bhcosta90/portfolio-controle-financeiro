<?php

namespace Modules\Cobranca\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Cobranca\Models\Frequencia;

final class FrequenciaService
{
    public function __construct(private Frequencia $repository)
    {
        //
    }

    public function data($filter = [])
    {
        return $this->repository->orderBy($filter['ordem'] ?? 'ordem_frequencia')->orderBy('nome');
    }

    public function webStore($data)
    {
        $data['tipo'] = "{$data['tipo']}|days";
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
        if (isset($data['tipo'])) {
            $data['tipo'] = "{$data['tipo']}|days";
        }

        $obj = $this->repository->find($id);
        $obj->update($data);
        return $obj;
    }

    public function delete($id)
    {
        return $this->repository->where('id', $id)->delete();
    }

    public function pluck($ordem = 'ordem_frequencia')
    {
        return $this->data(['ordem' => $ordem])->where('ativo', 1)->get()->pluck('nome', 'uuid')->toArray();
    }

    public function registrDefault($obj)
    {
        if (Schema::hasTable('frequencias')) {

            $frequencias = [
                ['nome' => 'Mensal', 'tipo' => '30|days', 'ordem_frequencia' => 10, 'ordem_parcela' => 0],
                ['nome' => 'Semanal', 'tipo' => '7|days', 'ordem_frequencia' => 0, 'ordem_parcela' => 5],
                ['nome' => 'Quinzenal', 'tipo' => '15|days', 'ordem_frequencia' => 5, 'ordem_parcela' => 10],
                ['nome' => 'Trimestral', 'tipo' => '90|days', 'ordem_frequencia' => 15, 'ordem_parcela' => 15],
                ['nome' => 'Semestral', 'tipo' => '180|days', 'ordem_frequencia' => 20, 'ordem_parcela' => 20],
                ['nome' => 'Anual', 'tipo' => '365|days', 'ordem_frequencia' => 25, 'ordem_parcela' => 25],
            ];

            foreach ($frequencias as $freq) {
                $freq += [
                    'uuid' => (string) str()->uuid(),
                    'tenant_id' => (string) $obj->tenant_id,
                    'ativo' => true,
                ];
                DB::table('frequencias')->insert($freq);
            }
        }
    }

}
