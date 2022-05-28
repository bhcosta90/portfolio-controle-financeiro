<?php

namespace App\Repositories\Eloquent;

use App\Models\Account;
use App\Repositories\Presenters\PaginatorPresenter;
use Costa\Modules\Account\Entity\AccountEntity;
use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Relationship\Customer\Entity\CustomerEntity;
use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\Contracts\PaginationInterface;
use Costa\Shared\ValueObject\ModelObject;
use Costa\Shared\ValueObject\UuidObject;

class AccountRepository implements AccountRepositoryInterface
{
    public function __construct(
        protected Account $model,
    ) {
        //  
    }

    public function insert(EntityAbstract $entity): EntityAbstract
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'entity_type' => $entity->entity->type,
            'entity_id' => $entity->entity->id,
            'value' => $entity->value,
        ]);

        return $this->entity($obj);
    }

    public function update(EntityAbstract $entity): EntityAbstract
    {
        $obj = $this->findDb($entity->id);

        $obj->update([
            'value' => $entity->value,
        ]);

        return $this->entity($obj);
    }

    public function find(string|int $key): EntityAbstract
    {
        return $this->entity($this->findDb($key));
    }

    public function findDb(string|int $key): object|array
    {
        return $this->model->findOrFail($key);
    }

    public function delete(EntityAbstract $entity): bool
    {
        return $this->findDb($entity->id)->delete();
    }

    public function paginate(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): PaginationInterface
    {
        return new PaginatorPresenter($this->model->paginate());
    }

    public function all(?array $filter = null): array|object
    {
        return $this->model->get();
    }

    public function pluck(): array
    {
        return $this->model->pluck('name', 'id')->toArray();
    }

    public function findByEntity(ModelObject $entity): AccountEntity
    {
        $obj = $this->model->where('entity_type', $entity->type)
            ->where('entity_id', $entity->id)
            ->firstOrFail();
        return $this->entity($obj);
    }

    protected function entity(object $entity)
    {
        return new AccountEntity(
            new ModelObject($entity->entity_id, $entity->entity_type),
            $entity->value,
            new UuidObject($entity->id)
        );
    }
}
