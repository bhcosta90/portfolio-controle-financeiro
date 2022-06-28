<?php

namespace App\Repository\Eloquent;

use App\Models\Recurrence;
use App\Repository\Presenters\PaginatorPresenter;
use Core\Financial\BankAccount\Domain\BankAccountEntity;
use Core\Financial\Recurrence\Domain\RecurrenceEntity;
use Core\Financial\Recurrence\Repository\RecurrenceRepositoryInterface;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Interfaces\PaginationInterface;

class RecurrenceEloquentRepository implements RecurrenceRepositoryInterface
{
    public function __construct(
        protected Recurrence $model,
    ) {
        //  
    }

    public function insert(EntityAbstract $entity): bool
    {
        return (bool) $this->model->create([
            'id' => $entity->id(),
            'name' => $entity->name->value,
            'days' => $entity->days,
        ]);
    }

    public function update(EntityAbstract $entity): bool
    {
        $obj = $this->findDb($entity->id);

        return $obj->update([
            'name' => $entity->name->value,
            'days' => $entity->days,
        ]);
    }

    public function find(string|int $key): EntityAbstract
    {
        return $this->entity($this->findDb($key));
    }

    public function findDb(string|int $key): object|array
    {
        return $this->model->where('id', $key)->first();
    }

    public function exist(string|int $key): bool
    {
        return $this->model->findDb($key)->count();
    }

    public function delete(EntityAbstract $entity): bool
    {
        return $this->findDb($entity->id)->delete();
    }

    public function paginate(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): PaginationInterface
    {
        $result = $this->model->orderBy('days', 'asc');

        return new PaginatorPresenter($result->paginate(
            page: $page,
            perPage: $totalPage,
        ));
    }

    public function all(?array $filter = null): array|object
    {
        return $this->model->get();
    }

    public function pluck(?array $filter = null): array
    {
        return $this->model->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
    }

    public function entity(object $input): EntityAbstract
    {
        return RecurrenceEntity::create(
            name: $input->name,
            days: $input->days,
            id: $input->id,
            createdAt: $input->created_at
        );
    }
}
