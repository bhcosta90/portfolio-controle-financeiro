<?php

namespace App\Repositories\Eloquent;

use App\Models\Charge;
use App\Repositories\Presenters\PaginatorPresenter;
use Costa\Modules\Charge\Receive\Entity\ChargeEntity;
use Costa\Modules\Charge\Receive\Repository\ChargeRepositoryInterface;
use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\Contracts\PaginationInterface;
use Costa\Shared\ValueObject\ModelObject;
use Costa\Shared\ValueObject\UuidObject;
use DateTime;

class ChargeRepository implements ChargeRepositoryInterface
{
    public function __construct(
        protected Charge $model,
    ) {
        //  
    }

    protected function entity(object $entity)
    {
        return new ChargeEntity(
            title: $entity->title,
            base: $entity->uuid,
            description: $entity->description,
            relationship: new ModelObject($entity->relationship_id, $entity->relationship_type),
            value: $entity->value,
            date: new DateTime($entity->date_due),
            dateStart: new DateTime($entity->date_start),
            dateFinish: new DateTime($entity->date_finish),
            recurrence: $entity->recurrence ? new UuidObject($entity->recurrence) : null,
            id: new UuidObject($entity->id),
            createdAt: new DateTime($entity->created_at),
        );
    }

    public function insert(EntityAbstract $entity): EntityAbstract
    {
        dump($entity);
        
        $obj = $this->model->create([
            'id' => $entity->id(),
            'entity' => get_class($entity),
            'name' => $entity->name->value,
            'document_type' => $entity->document?->type->value,
            'document_value' => $entity->document?->document,
        ]);

        return $this->entity($obj);
    }

    public function update(EntityAbstract $entity): EntityAbstract
    {
        $obj = $this->findDb($entity->id);

        $obj->update([
            'name' => $entity->name->value,
            'document_type' => $entity->document?->type->value,
            'document_value' => $entity->document?->document,
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
        $result = $this->model
            ->where('entity', CustomerEntity::class)
            ->orderBy('name', 'asc');
            
        return new PaginatorPresenter($result->paginate(
            page: $page,
            perPage: $totalPage,
        ));
    }

    public function all(?array $filter = null): array|object
    {
        return $this->model->get();
    }

    public function pluck(): array
    {
        return $this->model->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
    }
}
