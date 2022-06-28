<?php

namespace App\Repository\Eloquent;

use App\Models\Payment;
use App\Repository\Presenters\PaginatorPresenter;
use Core\Financial\Account\Domain\AccountEntity;
use Core\Financial\Payment\Domain\PaymentEntity;
use Core\Financial\Payment\Repository\PaymentRepositoryInterface;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Interfaces\PaginationInterface;
use Core\Shared\ValueObjects\EntityObject;
use Exception;

class PaymentEloquentRepository implements PaymentRepositoryInterface
{

    public function __construct(
        private Payment $model,
    ) {
    }

    public function insert(EntityAbstract $entity): bool
    {
        return (bool) $this->model->create([
            'id' => $entity->id(),
            'value' => $entity->value,
            'entity_id' => $entity->entity->id,
            'entity_type' => $entity->entity->type,
            'status' => $entity->status->value,
            'date' => $entity->date->format('Y-m-d'),
            'account_from_id' => $entity->accountFrom?->id(),
            'account_to_id' => $entity->accountTo?->id(),
        ]);
    }

    public function update(EntityAbstract $entity): bool
    {
        $obj = $this->model->find($entity->id());
        return $obj->update([
            'status' => $entity->status->value,
        ]);
    }

    public function find(string|int $key): EntityAbstract
    {
        return $this->entity($this->model->find($key));
    }

    public function exist(string|int $key): bool
    {
        return (bool) $this->model->find($key);
    }

    public function delete(EntityAbstract $entity): bool
    {
        return $this->model->find($entity->id())->delete();
    }

    public function paginate(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): PaginationInterface
    {
        $result = $this->model
            ->orderBy('created_at', 'asc');

        return new PaginatorPresenter($result->paginate(
            page: $page,
            perPage: $totalPage,
        ));
    }

    public function pluck(?array $filter = null): array
    {
        throw new Exception('Pluck do not implemented');
    }

    public function entity(object $input): EntityAbstract
    {
        return PaymentEntity::create(
            value: $input->value,
            date: $input->date,
            entity: new EntityObject($input->entity_id, $input->entity_type),
            accountFrom: $input->account_from_id
                ? AccountEntity::create(
                    new EntityObject($input->account_from_id, $input->account_from_type),
                    $input->account_from_value
                )
                : null,
            accountTo: $input->account_to_id
                ? AccountEntity::create(
                    new EntityObject($input->account_to_id, $input->account_to_type),
                    $input->account_to_value
                ) : null,
            id: $input->id,
            createdAt: $input->created_at,
        );
    }


    public function findPaymentExecuteByDate(string $date)
    {
        //
    }
}
