<?php

namespace App\Repository\Eloquent;

use App\Models\Payment;
use App\Repository\Abstracts\EloquentAbstract;
use App\Repository\Presenters\LazyCollectionPresenter;
use App\Repository\Presenters\PaginatorPresenter;
use App\Repository\Presenters\ResultPresenter;
use Core\Application\AccountBank\Domain\AccountBankEntity;
use Core\Application\Payment\Domain\PaymentEntity;
use Core\Application\Payment\Repository\PaymentRepository;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Interfaces\PaginationInterface;
use Core\Shared\Interfaces\ResultInterface;
use Core\Shared\ValueObjects\EntityObject;
use Exception;

class PaymentEloquent extends EloquentAbstract implements PaymentRepository
{
    public function __construct(
        protected Payment $model,
    ) {
        //
    }

    public function insert(EntityAbstract $entity): bool
    {
        $obj = $this->model->create([
            'id' => $entity->id(),
            'tenant_id' => $entity->tenant,
            'value' => $entity->value->value,
            'value_bank' => $entity->bankValue ?? null,
            'status' => $entity->status->value,
            'type' => $entity->type->value,
            'relationship_id' => $entity->relationship ? $entity->relationship->id : null,
            'relationship_type' => $entity->relationship ? $entity->relationship->type : null,
            'charge_id' => $entity->charge ? $entity->charge->id : null,
            'charge_type' => $entity->charge ? $entity->charge->type : null,
            'date' => $entity->date->format('Y-m-d H:i:s'),
            'account_bank_id' => $entity->bank,
            'title' => $entity->title->value,
            'resume' => $entity->resume->value,
            'relationship_name' => $entity->name ? $entity->name->value : null,
        ]);

        return (bool) $obj;
    }

    public function update(EntityAbstract $entity): bool
    {
        $obj = $this->findOrFail($entity->id());
        return $obj->update([
            'status' => $entity->status->value,
            'value_bank' => $entity->bankValue ?? null,
        ]);
    }

    public function find(string|int $key): EntityAbstract
    {
        $obj = $this->findOrFail($key);
        return $this->entity($obj);
    }

    public function paginate(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): PaginationInterface
    {
        $result = $this->model
            ->select(
                'payments.*',
                'account_banks.name as bank_name',
            )
            ->leftJoin('account_banks', 'account_banks.id', '=', 'payments.account_bank_id')
            ->orderBy('payments.created_at', 'desc');

        return new PaginatorPresenter($result->paginate(
            page: $page,
            perPage: $totalPage,
        ));
    }

    public function report(?array $filter = null, ?int $page = 1, ?int $totalPage = 15): ResultInterface
    {
        $result = $this->model
            ->select(
                'payments.*',
                'account_banks.name as bank_name',
            )
            ->leftJoin('account_banks', 'account_banks.id', '=', 'payments.account_bank_id')
            ->orderBy('payments.created_at', 'desc')
            ->skip(($page - 1) * $totalPage)
            ->take($totalPage)
            ->cursor();

        return new LazyCollectionPresenter($result);
    }

    public function updateStatus(string $date, int $filterStatus, int $status): bool
    {
        return $this->model
            ->where('date', '<', $date)
            ->where('status', $filterStatus)
            ->update(['status' => $status]);
    }

    public function getListStatus(int $status, int $totalPage = 50): ResultInterface
    {
        return new ResultPresenter($this->model
            ->where('status', $status)
            ->limit($totalPage)
            ->orderBy('created_at')
            ->get());
    }

    public function entity(object $input): PaymentEntity
    {
        return PaymentEntity::create(
            relationship: new EntityObject($input->relationship_id, $input->relationship_type),
            charge: $input->charge_id ? new EntityObject($input->charge_id, $input->charge_type) : null,
            bank: $input->account_bank_id,
            value: $input->value,
            status: $input->status,
            type: $input->type,
            date: $input->date,
            id: $input->id,
            createdAt: $input->created_at,
            title: $input->title,
            resume: $input->resume,
            name: $input->relationship_name,
        );
    }
}
