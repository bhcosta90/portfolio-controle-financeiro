<?php

namespace App\Repository\Eloquent;

use App\Models\Charge;
use App\Repository\Abstracts\EloquentAbstract;
use App\Repository\Presenters\PaginatorPresenter;
use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Application\Charge\Modules\Receive\Repository\ChargeReceiveRepository;
use Core\Application\Charge\Shared\Enums\ChargeStatusEnum;
use Core\Application\Charge\Shared\Enums\ChargeTypeEnum;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Interfaces\PaginationInterface;
use Core\Shared\ValueObjects\ParcelObject;

class ChargeReceiveEloquent extends EloquentAbstract implements ChargeReceiveRepository
{
    public function __construct(
        protected Charge $model,
    ) {
        //
    }

    public function insert(EntityAbstract $entity): bool
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'group_id' => $entity->group,
            'title' => $entity->title->value,
            'resume' => $entity->resume?->value,
            'relationship_id' => $entity->customer->id,
            'relationship_type' => $entity->customer->type,
            'entity' => get_class($entity),
            'recurrence_id' => $entity->recurrence,
            'value_charge' => $entity->value->value,
            'status' => $entity->status->value,
            'date' => $entity->date->format('Y-m-d'),
            'type' => ChargeTypeEnum::DEBIT,
            'parcel_actual' => 1,
            'parcel_total' => 1,
        ]);

        return (bool) $obj;
    }

    public function insertParcel(ReceiveEntity $entity, ParcelObject $parcel): bool
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'group_id' => $entity->group,
            'title' => $entity->title->value,
            'resume' => $entity->resume?->value,
            'relationship_id' => $entity->customer->id,
            'relationship_type' => $entity->customer->type,
            'entity' => get_class($entity),
            'recurrence_id' => $entity->recurrence,
            'value_charge' => $entity->value->value,
            'status' => $entity->status->value,
            'date' => $entity->date->format('Y-m-d'),
            'type' => ChargeTypeEnum::DEBIT,
            'parcel_actual' => $parcel->parcel,
            'parcel_total' => $parcel->total,
        ]);

        return (bool) $obj;
    }

    public function update(EntityAbstract $entity): bool
    {
        $obj = $this->model->findOrFail($entity->id());
        return $obj->update([
            'title' => $entity->title->value,
            'resume' => $entity->resume?->value,
            'relationship_id' => $entity->customer->id,
            'recurrence_id' => $entity->recurrence,
            'value_charge' => $entity->value->value,
            'value_pay' => $entity->pay->value,
            'status' => $entity->status->value,
            'date' => $entity->date->format('Y-m-d'),
        ]);
    }

    public function find(string|int $key): EntityAbstract
    {
        $obj = $this->model->find($key);

        return ReceiveEntity::create(
            tenant: $obj->tenant_id,
            title: $obj->title,
            resume: $obj->resume,
            customer: $obj->relationship_id,
            recurrence: $obj->recurrence_id,
            value: $obj->value_charge,
            pay: $obj->value_pay,
            group: $obj->group_id,
            date: $obj->date,
            status: $obj->status,
            id: $obj->id,
            createdAt: $obj->created_at,
        );
    }

    public function paginate(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): PaginationInterface
    {
        $result = $this->model
            ->select(
                'charges.*',
                'relationships.name as relationship_name',
                'recurrences.name as recurrence_name',
            )
            ->join('relationships', 'relationships.id', '=', 'charges.relationship_id')
            ->leftJoin('recurrences', 'recurrences.id', '=', 'charges.recurrence_id')
            ->where('charges.entity', ReceiveEntity::class)
            ->where(fn ($q) => ($f = $filter['name'] ?? null)
                ? $q->where('relationships.name', 'like', "%{$f}%")
                : null)
            ->where(fn ($q) => ($f = $filter['title'] ?? null)
                ? $q->where('charges.title', 'like', "%{$f}%")
                : null)
            ->whereIn('charges.status', [ChargeStatusEnum::PENDING, ChargeStatusEnum::PARTIAL])
            ->orderBy('charges.date')
            ->orderBy('relationships.name');

        return new PaginatorPresenter($result->paginate(
            page: $page,
            perPage: $totalPage,
        ));
    }
}
