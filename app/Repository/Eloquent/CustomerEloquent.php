<?php

namespace App\Repository\Eloquent;

use App\Models\Relationship;
use App\Repository\Abstracts\EloquentAbstract;
use App\Repository\Presenters\PaginatorPresenter;
use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Interfaces\PaginationInterface;

class CustomerEloquent extends EloquentAbstract implements CustomerRepository
{
    public function __construct(
        protected Relationship $model,
    )
    {
        //
    }

    public function insert(EntityAbstract $entity): bool
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'tenant_id' => $entity->tenant,
            'name' => $entity->name->value,
            'entity' => get_class($entity),
        ]);

        return (bool)$obj;
    }

    public function update(EntityAbstract $entity): bool
    {
        $obj = $this->model->findOrFail($entity->id());
        return $obj->update([
            'name' => $entity->name->value,
            'value' => $entity->value,
        ]);
    }

    public function find(string|int $key): EntityAbstract
    {
        $obj = $this->model->find($key);

        return CustomerEntity::create(
            tenant: $obj->tenant_id,
            name: $obj->name,
            value: $obj->value,
            id: $obj->id,
            createdAt: $obj->created_at,
        );
    }

    public function paginate(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): PaginationInterface
    {
        $result = $this->model
            ->where('relationships.entity', CustomerEntity::class)
            ->where(fn($q) => ($f = $filter['name'] ?? null)
                ? $q->where('relationships.name', 'like', "%{$f}%")
                : null)
            ->orderBy('relationships.name');

        return new PaginatorPresenter($result->paginate(
            page: $page,
            perPage: $totalPage,
        ));
    }

    public function pluck(?array $filter = null): array
    {
        return $this->model->where('entity', $filter['entity'])->pluck($this->getValuePluck(), 'id')->toArray();
    }
}
