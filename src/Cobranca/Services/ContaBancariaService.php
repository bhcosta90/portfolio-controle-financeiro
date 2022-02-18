<?php

namespace Modules\Cobranca\Services;

use Modules\Cobranca\Models\ContaBancaria;

final class ContaBancariaService
{
    public function __construct(private ContaBancaria $repository)
    {
        //
    }

    public function data()
    {
        return $this->repository->with(['banco', 'banco.entidade'])
            ->orderBy('id', 'desc');
    }

    public function webStore($data)
    {
        $data['valor'] = str()->numberBrToEn($data['valor']);
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
        return $this->data()->where('ativo', 1)->get()->pluck('nomeSelect', 'uuid')->toArray();
    }
}
