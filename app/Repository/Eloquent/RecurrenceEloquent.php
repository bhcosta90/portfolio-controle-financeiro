<?php

namespace App\Repository\Eloquent;

use App\Models\Recurrence;
use App\Repository\Abstracts\EloquentAbstract;
use App\Repository\Presenters\PaginatorPresenter;
use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Interfaces\PaginationInterface;

class RecurrenceEloquent extends EloquentAbstract implements RecurrenceRepository
{
    public function __construct(
        protected Recurrence $model,
    ) {
        //
    }

    public function insert(EntityAbstract $entity): bool
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'tenant_id' => $entity->tenant,
            'name' => $entity->name->value,
            'days' => $entity->days->value,
        ]);

        return (bool) $obj;
    }

    public function update(EntityAbstract $entity): bool
    {
        $obj = $this->model->findOrFail($entity->id());
        return $obj->update([
            'name' => $entity->name->value,
            'name' => $entity->name->value,
            'days' => $entity->days->value,
        ]);
    }

    public function find(string|int $key): EntityAbstract
    {
        $obj = $this->model->find($key);

        return RecurrenceEntity::create(
            tenant: $obj->tenant_id,
            name: $obj->name,
            days: $obj->days,
            id: $obj->id,
            createdAt: $obj->created_at,
        );
    }

    public function paginate(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): PaginationInterface
    {
        $result = $this->model
            ->where(fn ($q) => ($f = $filter['name'] ?? null)
                ? $q->where('recurrences.name', 'like', "%{$f}%")
                : null)
            ->orderBy('recurrences.days')
            ->orderBy('recurrences.name');

        return new PaginatorPresenter($result->paginate(
            page: $page,
            perPage: $totalPage,
        ));
    }
}
