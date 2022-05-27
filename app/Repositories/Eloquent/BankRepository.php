<?php

namespace App\Repositories\Eloquent;

use App\Models\Bank;
use App\Repositories\Presenters\PaginatorPresenter;
use Costa\Modules\Account\Entities\BankEntity;
use Costa\Modules\Account\Repository\BankRepositoryInterface;
use Costa\Shareds\Abstracts\EntityAbstract;
use Costa\Shareds\Contracts\PaginationInterface;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\UuidObject;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class BankRepository implements BankRepositoryInterface
{
    public function __construct(private Bank $model)
    {
        //
    }
    
    public function insert(EntityAbstract $entity): BankEntity
    {
        $model = $this->model->create([
            'uuid' => $entity->id,
            'name' => $entity->name->value,
            'active' => $entity->active,
        ]);

        return $this->toEntity($model);
    }

    public function find(int|string $id): BankEntity
    {
        return $this->toEntity($this->findByDb($id));
    }

    public function update(EntityAbstract $entity): BankEntity
    {
        if ($model = $this->findByDb($entity->id())) {
            $model->update([
                'name' => $entity->name->value,
                'active' => $entity->active,
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
        if ($model = $this->model
            ->select('banks.*', 'accounts.value')
            ->leftJoin('accounts', 'accounts.model_id', '=', 'banks.uuid')
            ->where('uuid', $id)
            ->firstOrFail()) {
            return $model;
        }

        throw new NotFoundResourceException(__('Supplier not found'));
    }

    public function addValue(string|int $id, float $value): BankEntity
    {
        $obj = $this->model->where('model_id', $id)->firstOrFail();
        $obj->increment('value', $value);

        return new BankEntity(
            name: $obj->name
        );
    }

    public function subValue(string|int $id, float $value): BankEntity
    {
        $obj = $this->model->where('model_id', $id)->firstOrFail();
        $obj->decrement('value', $value);

        return new BankEntity(
            name: $obj->name
        );
    }

    public function pluck(): array
    {
        return $this->model->orderBy('name')->where('active', true)->pluck('name', 'uuid')->toArray();
    }

    public function total(): float
    {
        return $this->model
            ->join('accounts', 'accounts.model_id', '=', 'banks.uuid')
            ->sum('accounts.value');
    }

    public function toEntity(object $data): BankEntity
    {
        return new BankEntity(
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
            ->orderBy('name', 'asc')
            ->leftJoin('accounts', 'accounts.model_id', '=', 'banks.uuid')
            ->where(fn ($q) => ($f = $filter['name'] ?? null) ? $q->where('name', 'like', "%{$f}%") : null)
            ->paginate(
                perPage: $totalPage,
                page: $page,
            );

        return new PaginatorPresenter($data);
    }
}
