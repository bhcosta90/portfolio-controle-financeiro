<?php

namespace App\Repository\Eloquent;

use App\Models\BankAccount;
use App\Repository\Presenters\PaginatorPresenter;
use Core\Financial\BankAccount\Domain\BankAccountEntity;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Interfaces\PaginationInterface;

class BankAccountEloquentRepository implements BankAccountRepositoryInterface
{
    public function __construct(
        protected BankAccount $model,
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
        return $this->model
            ->select('bank_accounts.*', 'accounts.value')
            ->join('accounts', function ($q) {
                $q->on('accounts.entity_id', '=', 'bank_accounts.id')
                    ->where('accounts.entity_type', BankAccountEntity::class);
            })
            ->where('bank_accounts.id', $key)->first();
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
        $result = $this->model
            ->select('bank_accounts.*', 'accounts.value')
            ->join('accounts', function ($q) {
                $q->on('accounts.entity_id', '=', 'bank_accounts.id')
                    ->where('accounts.entity_type', BankAccountEntity::class);
            })
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

    public function pluck(?array $filter = null): array
    {
        return $this->model->orderBy('name', 'asc')
            ->where('entity', CompanyEntity::class)
            ->pluck('name', 'id')->toArray();
    }

    public function entity(object $input): EntityAbstract
    {
        return BankAccountEntity::create(
            name: $input->name,
            value: $input->value,
            id: $input->id,
            createdAt: $input->created_at
        );
    }
}
