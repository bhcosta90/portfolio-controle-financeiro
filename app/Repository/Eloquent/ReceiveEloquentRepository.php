<?php

namespace App\Repository\Eloquent;

use App\Models\Charge;
use App\Repository\Presenters\PaginatorPresenter;
use Core\Financial\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Financial\Charge\Modules\Receive\Repository\ReceiveRepositoryInterface;
use Core\Financial\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Interfaces\PaginationInterface;
use DateTime;
use Exception;

class ReceiveEloquentRepository implements ReceiveRepositoryInterface
{
    public function __construct(private Charge $model)
    {
        //
    }

    public function insert(EntityAbstract $entity): bool
    {
        return (bool) $this->model->create([
            'id' => $entity->id(),
            'entity' => get_class($entity),
            'relationship_type' => get_class($entity->customer),
            'recurrence_id' => $entity->recurrence?->id(),
            'relationship_id' => $entity->customer->id(),
            'group_id' => (string) $entity->group,
            'status' => $entity->status->value,
            'type' => $entity->type->value,
            'value_charge' => $entity->value,
            'date' => $entity->date->format('Y-m-d'),
        ]);
    }

    public function update(EntityAbstract $entity): bool
    {
        $obj = $this->model->find($entity->id());
        return $obj->update([
            'recurrence_id' => $entity->recurrence?->id(),
            'relationship_id' => $entity->customer->id(),
            'group_id' => (string) $entity->group,
            'status' => $entity->status->value,
            'type' => $entity->type->value,
            'value_charge' => $entity->value,
            'date' => $entity->date->format('Y-m-d'),
        ]);
    }

    public function find(string|int $key): EntityAbstract
    {
        $obj = $this->model->where('charges.id', $key)
            ->select(
                'charges.*',
                'relationships.name as relationship_name'
            )
            ->join('relationships', 'relationships.id', '=', 'charges.relationship_id')
            ->first();
        return $this->entity($obj);
    }

    public function exist(string|int $key): bool
    {
        return (bool) $this->model->find($key);
    }

    public function delete(EntityAbstract $entity): bool
    {
        return $this->model->find($entity->id())->delete();
    }

    public function paginate(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): PaginationInterface
    {
        $result = $this->model
            ->select(
                'charges.*',
                'relationships.name as relationship_name'
            )
            ->join('relationships', 'relationships.id', '=', 'charges.relationship_id')
            ->where('charges.entity', ReceiveEntity::class)
            ->orderBy('charges.date', 'asc');

        return new PaginatorPresenter($result->paginate(
            page: $page,
            perPage: $totalPage,
        ));
    }

    public function pluck(?array $filter = null): array
    {
        throw new Exception('Pluck do not implemented');
    }

    public function entity(object $input): EntityAbstract
    {
        return ReceiveEntity::create(
            group: $input->group_id,
            value: $input->value_charge,
            customer: CustomerEntity::create($input->relationship_name, null, null, $input->relationship_id),
            type: $input->type,
            date: $input->date,
            recurrence: null,
            pay: $input->value_pay ?: 0,
            status: $input->status,
            id: $input->id,
            createdAt: (new DateTime($input->created_at))->format('Y-m-d H:i:s'),
        );
    }
}
