<?php

namespace App\Repository\Eloquent;

use App\Models\Transaction;
use App\Repository\Presenters\PaginatorPresenter;
use App\Repository\Presenters\ResultPresenter;
use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Application\Transaction\Domain\TransactionEntity;
use Core\Application\Transaction\Repository\TransactionRepository;
use Core\Application\Transaction\Shared\Enums\TransactionStatusEnum;
use Core\Shared\Interfaces\PaginationInterface;
use Core\Shared\Interfaces\ResultInterface;
use DateTime;

class TransactionEloquent implements TransactionRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = app(Transaction::class);
    }

    public function insert(TransactionEntity $entity): bool
    {
        return (bool) $this->model->create([
            'id' => $entity->id(),
            'group_id' => $entity->group,
            'tenant_id' => $entity->tenant,
            'account_to_id' => $entity->accountTo,
            'account_from_id' => $entity->accountFrom,
            'entity_id' => $entity->entity->id,
            'entity_type' => $entity->entity->type,
            'relationship_id' => $entity->relationship?->id,
            'relationship_type' => $entity->relationship?->type,
            'relationship_name' => $entity->relationship?->value,
            'title' => $entity->title,
            'value' => $entity->value->value,
            'previous_value' => $entity->previousValue,
            'type' => $entity->type->value,
            'status' => $entity->status->value,
            'date' => $entity->dateExecute->format('Y-m-d'),
        ]);
    }

    public function find(string|int $id): TransactionEntity
    {
        $result = $this->model->where('id', $id)->first();
        return $this->toEntity($result);
    }

    public function toEntity(object $result): TransactionEntity
    {
        return TransactionEntity::create(
            tenant: $result->tenant_id,
            group: $result->group_id,
            title: $result->title,
            accountTo: $result->account_to_id,
            accountFrom: $result->account_from_id,
            transaction_id: $result->entity_id,
            transaction_type: $result->entity_type,
            relationship_id: $result->relation_id ?? $result->relationship_id,
            relationship_type: $result->relation_type ?? $result->relationship_type,
            relationship_name: $result->relation_name ?? $result->relationship_name,
            value: $result->value,
            type: $result->type,
            status: $result->status,
            date_execute: date('Y-m-d'),
            id: $result->id,
            createdAt: $result->created_at,
        );
    }

    public function update(TransactionEntity $entity): bool
    {
        return $this->model->where('id', $entity->id())->update([
            'previous_value' => $entity->previousValue,
            'status' => $entity->status->value,
        ]);
    }

    public function filterByDate(DateTime $start, DateTime $end)
    {
        $this->model = $this->model->whereBetween('transactions.created_at', [
            $start->format('Y-m-d H:i:s'),
            $end->format('Y-m-d H:i:s')
        ]);
    }

    public function filterByName(string $name)
    {
        $this->model = $this->model->where('transactions.relationship_name', 'like', "%{$name}%");
    }

    public function paginate(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): PaginationInterface
    {
        $result = $this->model->select('transactions.*', 'banks.name as bank_name')
            ->join('accounts', 'accounts.id', '=', 'transactions.account_to_id')
            ->leftJoin('banks', 'banks.id', '=', 'accounts.entity_id')
            ->whereNotIn('accounts.entity_type', [CustomerEntity::class, CompanyEntity::class])
            ->orderBy('transactions.created_at', 'desc');

        return new PaginatorPresenter($result->paginate(
            page: $page,
            perPage: $totalPage,
        ));
    }

    public function report(int $limit, int $page): ResultInterface
    {
        return new ResultPresenter($this->model->select('transactions.*', 'banks.name as bank_name')
            ->join('accounts', 'accounts.id', '=', 'transactions.account_to_id')
            ->leftJoin('banks', 'banks.id', '=', 'accounts.entity_id')
            ->whereNotIn('accounts.entity_type', [CustomerEntity::class, CompanyEntity::class])
            ->orderBy('transactions.created_at', 'desc')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->cursor());
    }

    public function getTransactionInDate(DateTime $date, int $limit, int $page): ResultInterface
    {
        $result = $this->model->where('transactions.date', '<=', $date->format('Y-m-d'))
            ->where('transactions.status', TransactionStatusEnum::PENDING)
            ->take($limit)
            ->skip($limit * $page);

        return new ResultPresenter($result->get());
    }

    public function delete(TransactionEntity $entity): bool
    {
        $obj = $this->model->find($entity->id());
        return $this->model->where('group_id', $obj->group_id)->delete();
    }
}
