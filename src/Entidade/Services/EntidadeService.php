<?php

namespace Modules\Entidade\Services;

use Illuminate\Support\Facades\DB;
use Modules\Entidade\Models\Entidade;

final class EntidadeService
{
    public function __construct(private Entidade $repository)
    {
        //
    }

    public function create(array $data)
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

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $obj = $this->repository->find($id);
            $obj->entidade->delete();
            return $obj->delete();
        });
    }

    public function webUpdate($data, $id){
        $obj = $this->repository->find($id);
        $obj->update($data);
        return $obj;
    }
}
