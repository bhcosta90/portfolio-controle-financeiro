<?php

namespace App\Repository\Eloquent;

use App\Models\Bank;
use App\Repository\Abstracts\EloquentAbstract;
use App\Repository\Presenters\PaginatorPresenter;
use Core\Application\BankAccount\Modules\Bank\Domain\BankEntity;
use Core\Application\BankAccount\Modules\Bank\Repository\BankRepository;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Interfaces\PaginationInterface;

class BankEloquent extends EloquentAbstract implements BankRepository
{
    protected function model()
    {
        return app(Bank::class);
    }

    public function insert(EntityAbstract $entity): bool
    {
        return (bool) Bank::create([
            'id' => $entity->id(),
            'tenant_id' => $entity->tenant,
            'name' => $entity->name->value,
            'active' => $entity->active,
            'code' => $entity->bank?->code,
            'account' => $entity->bank?->account->account,
            'account_digit' => $entity->bank?->account?->digit,
            'agency' => $entity->bank?->agency->account,
            'agency_digit' => $entity->bank?->agency?->digit,
        ]);
    }

    public function update(EntityAbstract $entity): bool
    {
        $obj = $this->findOrFail($entity->id());

        return $obj->update([
            'name' => $entity->name->value,
            'code' => $entity->bank?->code,
            'account' => $entity->bank?->account->account,
            'account_digit' => $entity->bank?->account?->digit,
            'agency' => $entity->bank?->agency->account,
            'agency_digit' => $entity->bank?->agency?->digit,
            'active' => $entity->active,
        ]);
    }

    public function paginate(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): PaginationInterface
    {
        $result = $this->model
            ->select(
                'banks.*',
                'accounts.value as account_value',
                'accounts.id as account_id',
            )
            ->join('accounts', fn ($q) => $q->on('accounts.entity_id', '=', 'banks.id')
                ->where('accounts.entity_type', BankEntity::class))
            ->orderBy('banks.active', 'desc')
            ->orderBy('banks.name');

        return new PaginatorPresenter($result->paginate(
            page: $page,
            perPage: $totalPage,
        ));
    }

    public function pluck(?array $filter = null): array
    {
        $this->model = $this->model->where('active', true);
        return parent::pluck($filter);
    }

    public function find(string|int $key): EntityAbstract
    {
        $obj = $this->model
            ->select(
                'banks.*',
                'accounts.value as account_value',
                'accounts.id as account_id',
            )
            ->join('accounts', fn ($q) => $q->on('accounts.entity_id', '=', 'banks.id')
                ->where('accounts.entity_type', BankEntity::class));

        if (in_array(SoftDeletes::class, class_uses($obj))) {
            $obj = $obj->withTrashed();
        }

        return $this->toEntity($obj->where('banks.id', $key)->firstOrFail());
    }

    public function toEntity(object $obj): EntityAbstract
    {
        return BankEntity::create(
            tenant: $obj->tenant_id,
            name: $obj->name,
            value: $obj->account_value,
            active: $obj->active,
            bankCode: $obj->code,
            agency: $obj->agency,
            agencyDigit: $obj->agency_digit,
            account: $obj->account,
            accountDigit: $obj->account_digit,
            accountEntity: $obj->account_id,
            id: $obj->id,
            createdAt: $obj->created_at,
        );
    }
}
