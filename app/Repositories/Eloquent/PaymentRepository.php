<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Repositories\Presenters\PaginatorPresenter;
use Costa\Modules\Payment\Entity\PaymentEntity;
use Costa\Modules\Payment\Repository\PaymentRepositoryInterface;
use Costa\Modules\Payment\Shared\Enums\PaymentType;
use Costa\Shared\Abstracts\EntityAbstract;
use Costa\Shared\Contracts\PaginationInterface;
use Costa\Shared\ValueObject\Input\InputIntObject;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\ModelObject;
use Costa\Shared\ValueObject\UuidObject;
use DateTime;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function __construct(
        protected Payment $model,
    ) {
        //  
    }

    public function insert(EntityAbstract $entity): EntityAbstract
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'charge_id' => $entity->charge->id,
            'charge_type' => $entity->charge->type,
            'account_from_id' => $entity->accountFrom,
            'account_to_id' => $entity->accountTo,
            'relationship_id' => $entity->relationship,
            'date_schedule' => $entity->date->format('Y-m-d'),
            'value_transaction' => $entity->value,
            'value_payment' => $entity->value,
            'title' => $entity->title->value,
            'completed' => $entity->completed,
            'type' => $entity->type->value,
        ]);

        return $this->entity($obj);
    }

    public function update(EntityAbstract $entity): EntityAbstract
    {
        $obj = $this->findDb($entity->id);

        $obj->update([
            'completed' => $entity->completed,
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
        $result = $this->model
            ->select(
                'payments.id',
                'payments.completed',
                'payments.title',
                'payments.value_payment',
                'payments.date_schedule',
                'relationships.name',
            )
            ->join('charges', 'payments.charge_id', '=', 'charges.id')
            ->join('relationships', 'relationships.id', '=', 'charges.relationship_id')
            ->orderBy('payments.date_schedule', 'desc');

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
        return new PaymentEntity(
            relationship: $entity->relationship_id,
            charge: new ModelObject($entity->charge_id, $entity->charge_type),
            date: new DateTime($entity->date_schedule),
            value: $entity->value_payment,
            accountFrom: $entity->account_from_id,
            accountTo: $entity->account_to_id,
            id: new UuidObject($entity->id),
            createdAt: new DateTime($entity->create_at),
            type: PaymentType::from($entity->type),
            title: new InputNameObject($entity->title, true)
        );
    }
}
