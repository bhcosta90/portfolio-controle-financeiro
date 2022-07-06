<?php

namespace App\Repository\Eloquent;

use App\Models\AccountBank;
use App\Repository\Abstracts\EloquentAbstract;
use App\Repository\Presenters\PaginatorPresenter;
use Core\Application\AccountBank\Domain\AccountBankEntity;
use Core\Application\AccountBank\Repository\AccountBankRepository;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Interfaces\PaginationInterface;

class AccountBankEloquent extends EloquentAbstract implements AccountBankRepository
{
    public function __construct(
        protected AccountBank $model,
    ) {
        //
    }

    public function insert(EntityAbstract $entity): bool
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'value' => $entity->value,
            'name' => $entity->name->value,
            'bank_code' => $entity->bank?->code,
            'bank_agency' => $entity->bank?->agency->account,
            'bank_agency_digit' => $entity->bank?->agency?->digit,
            'bank_account' => $entity->bank?->account->account,
            'bank_account_digit' => $entity->bank?->account?->digit,
        ]);

        return (bool) $obj;
    }

    public function update(EntityAbstract $entity): bool
    {
        $obj = $this->model->findOrFail($entity->id());
        return $obj->update([
            'name' => $entity->name->value,
            'value' => $entity->value,
            'bank_code' => $entity->bank?->code,
            'bank_agency' => $entity->bank?->agency->account,
            'bank_agency_digit' => $entity->bank?->agency?->digit,
            'bank_account' => $entity->bank?->account->account,
            'bank_account_digit' => $entity->bank?->account?->digit,
        ]);
    }

    public function find(string|int $key): EntityAbstract
    {
        $obj = $this->findOrFail($key);

        return AccountBankEntity::create(
            name: $obj->name,
            value: $obj->value,
            tenant: $obj->tenant_id,
            bankCode: $obj->bank_code,
            agency: $obj->bank_agency,
            agencyDigit: $obj->bank_agency_digit,
            account: $obj->bank_account,
            accountDigit: $obj->bank_account_digit,
            id: $obj->id,
            createdAt: $obj->created_at,
        );
    }

    public function paginate(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): PaginationInterface
    {
        $result = $this->model
            ->where(fn ($q) => ($f = $filter['name'] ?? null)
                ? $q->where('account_banks.name', 'like', "%{$f}%")
                : null)
            ->orderBy('account_banks.name');

        return new PaginatorPresenter($result->paginate(
            page: $page,
            perPage: $totalPage,
        ));
    }
}
