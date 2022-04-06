<?php

namespace Modules\Cobranca\Services;

use Modules\Cobranca\Models\ContaBancaria;
use Modules\Entidade\Services\EntidadeService;

final class ContaBancariaService
{
    public function __construct(private ContaBancaria $repository)
    {
        //
    }

    public function data()
    {
        return $this->repository->with(['entidade'])
            ->orderBy('id', 'desc');
    }

    public function webStore($data)
    {
        if (!empty($data['entidade_id'])) {
            $data['entidade_id'] = $this->getEntidadeService()->find($data['entidade_id'])->id;
        }

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

        $data['entidade_id'] = $this->getEntidadeService()->find($data['entidade_id'])->id;

        $obj->update($data);
        return $obj;
    }

    public function delete($id)
    {
        return $this->repository->where('id', $id)->delete();
    }

    public function pluck()
    {
        return $this->data()->where('ativo', 1)->get()->pluck('nomeSelect', 'uuid')->toArray();
    }

    /**
     * @return EntidadeService
     */
    protected function getEntidadeService()
    {
        return app(EntidadeService::class);
    }
}
