<?php

namespace App\Repository\Abstracts;

use Core\Shared\Abstracts\EntityAbstract;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class EloquentAbstract
{
    public function exist(string|int $key): bool
    {
        return $this->model->where('id', $key)->count();
    }

    public function delete(EntityAbstract $entity): bool
    {
        return $this->findOrFail($entity->id())->delete();
    }

    protected function findOrFail(int|string $id)
    {
        return $this->model->where('id', $id)->firstOrFail();
    }

    public function getModel(string|int $key): object
    {
        $obj = $this->model;
        if (in_array(SoftDeletes::class, class_uses($obj))) {
            $obj = $obj->withTrashed();
        }
        return $obj->where('id', $key)->first();
    }

    public function pluck(?array $filter = null): array
    {
        return $this->model->pluck($this->getValuePluck(), 'id')->toArray();
    }

    protected function getValuePluck()
    {
        return 'name';
    }
}
