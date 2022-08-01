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
    protected function model()
    {
        return app(Relationship::class);
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
        $obj = $this->findOrFail($entity->id());
        return $obj->update([
            'name' => $entity->name->value,
            'value' => $entity->value,
        ]);
    }

    public function find(string|int $key): EntityAbstract
    {
        $obj = $this->model;
        if (in_array(SoftDeletes::class, class_uses($obj))) {
            $obj = $obj->withTrashed();
        }

        return $this->toEntity($obj
            ->select('relationships.*', 'accounts.id as account_id', 'accounts.value as account_value')
            ->join('accounts', fn($q) => $q->on('accounts.entity_id', '=', 'relationships.id'))
            ->where('relationships.id', $key)->firstOrFail());
    }

    public function toEntity(object $obj): EntityAbstract
    {
        return CustomerEntity::create(
            tenant: $obj->tenant_id,
            name: $obj->name,
            value: $obj->account_value,
            id: $obj->id,
            createdAt: $obj->created_at,
            account: $obj->account_id
        );
    }

    public function filterByName(string $name)
    {
        $this->model = $this->model->where('relationships.name', 'like', "%{$name}%");
    }

    public function paginate(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): PaginationInterface
    {
        $result = $this->model
            ->select('relationships.*', 'accounts.value')
            ->where('relationships.entity', CustomerEntity::class)
            ->join('accounts', fn ($q) => $q->on('accounts.entity_id', '=', 'relationships.id')
            ->where('accounts.entity_type', CustomerEntity::class))
            ->orderBy('relationships.name');
        

        return new PaginatorPresenter($result->paginate(
            page: $page,
            perPage: $totalPage,
        ));
    }

    public function pluck(?array $filter = null): array
    {
        return $this->model->where('entity', $filter['entity'])
                ->orderBy('relationships.name')
                ->pluck($this->getValuePluck(), 'id')
                ->toArray();
    }
}
