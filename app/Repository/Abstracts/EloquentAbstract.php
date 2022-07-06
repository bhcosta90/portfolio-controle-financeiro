<?php

namespace App\Repository\Abstracts;

use Core\Shared\Abstracts\EntityAbstract;

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

    public function pluck(?array $filter = null): array
    {
        return $this->model->pluck($this->getValuePluck(), 'id')->toArray();
    }

    protected function findOrFail(int|string $id)
    {
        return $this->model->where('id', $id)->firstOrFail();
    }

    protected function getValuePluck()
    {
        return 'name';
    }
}
