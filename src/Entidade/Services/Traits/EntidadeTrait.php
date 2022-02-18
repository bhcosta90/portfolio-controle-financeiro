<?php

namespace Modules\Entidade\Services\Traits;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Entidade\Services\EntidadeService;

trait EntidadeTrait
{
    protected abstract function model(): Model;

    public function data($filter = [])
    {
        return $this->repository->join('entidades', function ($q) {
            $q->on('entidades.entidade_id', '=', $this->model()->getTable() . '.id')
                ->where('entidades.entidade_type', get_class($this->model()));
        })
            ->select($this->model()->getTable() . '.*')
            ->orderBy('entidades.nome')
            ->orderBy('entidades.created_at', 'desc')
            ->where('entidades.tenant_id', tenant('id'))
            ->where(fn($q) => !empty($nome = $filter['nome'] ?? null) ? $q->where('entidades.nome', 'like', "%{$nome}%") : null)
            ->with(['entidade']);
    }

    public function find($id)
    {
        return $this->repository->whereHas('entidade', fn($q) => $q->where('uuid', $id))->first()->entidade;
    }

    public function webStore($data)
    {
        DB::beginTransaction();

        try {
            $obj = $this->model()->create();
            $data += [
                'entidade_type' => get_class($obj),
                'entidade_id' => $obj->id,
            ];

            $ret = $this->getEntidadeService()->create($data);
            DB::commit();
            return $ret;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function webUpdate($data, $id){
        $obj = $this->repository->find($id)->entidade;
        return $this->getEntidadeService()->webUpdate($data, $obj->id);
    }

    /**
     * @return EntidadeService
     */
    protected function getEntidadeService()
    {
        return app(EntidadeService::class);
    }
}
