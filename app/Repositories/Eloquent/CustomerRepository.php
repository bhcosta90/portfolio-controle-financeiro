<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Models\Relationship;
use App\Repositories\Presenters\PaginatorPresenter;
use Costa\Modules\Relationship\Entities\CustomerEntity;
use Costa\Modules\Relationship\Repository\CustomerRepositoryInterface;
use Costa\Shareds\Abstracts\EntityAbstract;
use Costa\Shareds\Contracts\PaginationInterface;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\UuidObject;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(
        private Customer $model,
        private Relationship $relationship
    ) {
        //
    }

    public function getName(int|string $id): ?string
    {
        return $this->model->where('uuid', $id)->first()->name;
    }

    public function insert(EntityAbstract $entity): CustomerEntity
    {
        $obj = $this->model->create([]);

        $model = $this->relationship->create([
            'uuid' => $entity->id,
            'name' => $entity->name->value,
            'relationship_type' => get_class($obj),
            'relationship_id' => $obj->id,
        ]);

        return $this->toEntity($model);
    }

    public function find(int|string $id): CustomerEntity
    {
        return $this->toEntity($this->findByDb($id));
    }

    public function pluck(): array
    {
        return $this->model->orderBy('relationships.name')
            ->join('relationships', function ($q) {
                $table = with(new $this->model)->getTable();
                $q->on('relationships.relationship_id', '=', $table . '.id')
                    ->where('relationships.relationship_type', $this->model::class);
            })->pluck('relationships.name', 'relationships.uuid')
            ->toArray();
    }

    public function update(EntityAbstract $entity): CustomerEntity
    {
        if ($model = $this->findByDb($entity->id())) {
            $model->update([
                'name' => $entity->name->value
            ]);
            return $this->toEntity($model);
        }
    }

    public function delete(int|string $id): bool
    {
        return $this->findByDb($id)->delete();
    }

    protected function findByDb(string $id)
    {
        if ($model = $this->relationship->where('uuid', $id)->firstOrFail()) {
            return $model;
        }

        throw new NotFoundResourceException(__('Customer not found'));
    }

    public function toEntity(object $data): CustomerEntity
    {
        return new CustomerEntity(
            id: new UuidObject($data->uuid),
            name: new InputNameObject($data->name),
        );
    }

    public function paginate(
        ?array $filter = null,
        ?array $order = null,
        ?int $page = 1,
        ?int $totalPage = 15
    ): PaginationInterface {

        $data = $this->model
            ->where(fn ($q) => ($f = $filter['name'] ?? null) ? $q->where('name', 'like', "%{$f}%") : null)
            ->whereNull('relationships.deleted_at')
            ->join('relationships', function ($q) {
                $table = with(new $this->model)->getTable();
                $q->on('relationships.relationship_id', '=', $table . '.id')
                    ->where('relationships.relationship_type', $this->model::class);
            })
            ->select('relationships.*')
            ->orderBy('relationships.name', 'asc')
            ->paginate(
                perPage: $totalPage,
                page: $page,
            );

        return new PaginatorPresenter($data);
    }
}
