<?php

namespace App\Repositories\Eloquent;

use App\Models\Recurrence;
use App\Repositories\Presenters\PaginatorPresenter;
use Costa\Modules\Recurrence\Entity\RecurrenceEntity;
use Costa\Modules\Recurrence\Repository\RecurrenceRepositoryInterface;
use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\Contracts\PaginationInterface;
use Costa\Shared\ValueObject\Input\InputIntObject;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\UuidObject;

class RecurrenceRepository implements RecurrenceRepositoryInterface
{
    public function __construct(
        protected Recurrence $model,
    ) {
        //  
    }

    public function insert(EntityAbstract $entity): EntityAbstract
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'name' => $entity->name->value,
            'days' => $entity->days->value,
        ]);

        return $this->entity($obj);
    }

    public function update(EntityAbstract $entity): EntityAbstract
    {
        $obj = $this->findDb($entity->id);

        $obj->update([
            'name' => $entity->name->value,
            'days' => $entity->days->value,
        ]);

        return $this->entity($obj);
    }

    public function find(string|int $key): EntityAbstract
    {
        return $this->entity($this->findDb($key));
    }

    public function findDb(string|int $key): object|array
    {
        return $this->model->where('id', $key)->firstOrFail();
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

    protected function entity(object $entity)
    {
        return new RecurrenceEntity(
            new InputNameObject($entity->name),
            new InputIntObject($entity->days),
            new UuidObject($entity->id)
        );
    }
}
