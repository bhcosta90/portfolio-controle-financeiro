<?php

namespace App\Repository\Abstracts;

use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class EloquentAbstract implements RepositoryInterface
{
    protected abstract function model();

    public function __construct()
    {
        $this->model = $this->model();
    }

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

    public function find(string|int $key): EntityAbstract
    {
        $obj = $this->model;
        if (in_array(SoftDeletes::class, class_uses($obj))) {
            $obj = $obj->withTrashed();
        }

        return $this->toEntity($obj->where('id', $key)->firstOrFail());
    }

    public function pluck(?array $filter = null): array
    {
        return $this->model->pluck($this->getValuePluck(), 'id')->toArray();
    }

    public function get(string|int $key): EntityAbstract
    {
        return $this->toEntity($this->find($key));
    }

    protected function getValuePluck()
    {
        return 'name';
    }
}
