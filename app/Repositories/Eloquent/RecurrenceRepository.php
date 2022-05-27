<?php

namespace App\Repositories\Eloquent;

use App\Models\Recurrence;
use App\Repositories\Presenters\PaginatorPresenter;
use Costa\Modules\Charge\Entities\RecurrenceEntity;
use Costa\Modules\Charge\Repository\RecurrenceRepositoryInterface;
use Costa\Shareds\Abstracts\EntityAbstract;
use Costa\Shareds\Contracts\PaginationInterface;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\UuidObject;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class RecurrenceRepository implements RecurrenceRepositoryInterface
{
    public function __construct(private Recurrence $model)
    {
        //
    }
    
    public function insert(EntityAbstract $entity): RecurrenceEntity
    {
        $model = $this->model->create([
            'uuid' => $entity->id,
            'name' => $entity->name,
            'days' => $entity->days,
        ]);

        return $this->toEntity($model);
    }

    public function find(int|string $id): RecurrenceEntity
    {
        return $this->toEntity($this->findByDb($id));
    }

    public function update(EntityAbstract $entity): RecurrenceEntity
    {
        if ($model = $this->findByDb($entity->id())) {
            $model->update([
                'name' => $entity->name,
                'days' => $entity->days,
            ]);
            return $this->toEntity($model);
        }
    }

    public function delete(int|string $id): bool
    {
        return $this->findByDb($id)->delete();
    }

    public function pluck(): array
    {
        return $this->model->orderBy('name')->pluck('name', 'uuid')->toArray();
    }

    protected function findByDb(string $id)
    {
        if ($model = $this->model->where('uuid', $id)->firstOrFail()) {
            return $model;
        }

        throw new NotFoundResourceException(__('Recurrence not found'));
    }

    public function verify(int|string $id): bool
    {
        return (bool) $this->model->where('uuid', $id)->count();
    }

    public function toEntity(object $data): RecurrenceEntity
    {
        return new RecurrenceEntity(
            id: new UuidObject($data->uuid),
            name: new InputNameObject($data->name),
            days: $data->days,
        );
    }

    public function paginate(
        ?array $filter = null,
        ?array $order = null,
        ?int $page = 1,
        ?int $totalPage = 15
    ): PaginationInterface {

        $data = $this->model
            ->orderBy('name', 'asc')
            ->where(fn ($q) => ($f = $filter['name'] ?? null) ? $q->where('name', 'like', "%{$f}%") : null)
            ->paginate(
                perPage: $totalPage,
                page: $page,
            );

        return new PaginatorPresenter($data);
    }
}
