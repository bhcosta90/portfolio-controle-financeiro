<?php

namespace App\Repositories\Eloquent;

use App\Models\Charge;
use App\Repositories\Presenters\PaginatorPresenter;
use Costa\Modules\Charge\Receive\Entity\ChargeEntity;
use Costa\Modules\Charge\Receive\Repository\ChargeRepositoryInterface;
use Costa\Modules\Relationship\Customer\Entity\CustomerEntity;
use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\Contracts\PaginationInterface;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\Input\InputValueObject;
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
            title: new InputNameObject($entity->title),
            base: new UuidObject($entity->uuid),
            description: new InputNameObject($entity->description, true),
            relationship: new ModelObject($entity->relationship_id, $entity->relationship_type),
            value: new InputValueObject($entity->value_charge),
            date: new DateTime($entity->date_due),
            dateStart: new DateTime($entity->date_start),
            dateFinish: new DateTime($entity->date_finish),
            recurrence: $entity->recurrence ? new UuidObject($entity->recurrence) : null,
            id: new UuidObject($entity->id),
            createdAt: new DateTime($entity->created_at),
            payValue: new InputValueObject($entity->value_pay, true),
        );
    }

    public function insert(EntityAbstract $entity): EntityAbstract
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'recurrence_id' => $entity->recurrence,
            'relationship_id' => $entity->relationship?->id,
            'relationship_type' => $entity->relationship ? get_class($entity->relationship) : null,
            'uuid' => $entity->base,
            'title' => $entity->title->value,
            'description' => $entity->description?->value,
            'date_start' => $entity->date->format('Y-m-d'),
            'date_finish' => $entity->dateStart->format('Y-m-d'),
            'date_due' => $entity->dateFinish->format('Y-m-d'),
            'parcel_total' => 0,
            'parcel_actual' => 0,
            'status' => $entity->status->value,
            'value_charge' => $entity->value->value,
            'value_pay' => 0,
            'entity' => get_class($entity),
        ]);

        return $this->entity($obj);
    }

    public function update(EntityAbstract $entity): EntityAbstract
    {
        $obj = $this->findDb($entity->id);

        $obj->update([
            'title' => $entity->title->value,
            'description' => $entity->description?->value,
            'date_due' => $entity->dateFinish->format('Y-m-d'),
            'relationship_id' => $entity->relationship?->id,
            'relationship_type' => $entity->relationship ? get_class($entity->relationship) : null,
            'value_charge' => $entity->value->value,
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
            ->select('charges.*', 'relationships.name as relationship_name')
            ->join('relationships', fn ($q) => $q->on('relationships.id', '=', 'charges.relationship_id')
            ->where('relationships.entity', CustomerEntity::class))
            ->where('charges.entity', ChargeEntity::class)
            ->orderBy('charges.date_due', 'asc');

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
