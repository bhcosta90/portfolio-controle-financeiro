<?php

namespace App\Repositories\Eloquent;

use App\Models\Bank;
use App\Repositories\Presenters\PaginatorPresenter;
use Costa\Modules\Bank\Entity\BankEntity;
use Costa\Modules\Bank\Repository\BankRepositoryInterface;
use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\Contracts\PaginationInterface;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\UuidObject;

class BankRepository implements BankRepositoryInterface
{
    public function __construct(
        protected Bank $model,
    ) {
        //  
    }

    public function insert(EntityAbstract $entity): EntityAbstract
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'name' => $entity->name->value,
        ]);

        return $this->entity($obj);
    }

    public function update(EntityAbstract $entity): EntityAbstract
    {
        $obj = $this->findDb($entity->id);

        $obj->update([
            'name' => $entity->name->value,
        ]);

        return $this->entity($obj);
    }

    public function find(string|int $key): EntityAbstract
    {
        return $this->entity($this->findDb($key));
    }

    public function findDb(string|int $key): object|array
    {
        return $this->model->find($key);
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
        $result = $this->model->select('banks.*', 'accounts.value')
            ->join('accounts', function($q){
                $q->on('accounts.entity_id', '=', 'banks.id')
                    ->where('accounts.entity_type', BankEntity::class);
            });
        
        return new PaginatorPresenter($result->paginate());
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
        return new BankEntity(
            new InputNameObject($entity->name),
            new UuidObject($entity->id),
        );
    }
}
