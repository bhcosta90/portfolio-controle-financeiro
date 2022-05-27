<?php

namespace App\Repositories\Eloquent;

use App\Models\Account;
use App\Models\Bank;
use App\Models\Charge;
use App\Models\Payment;
use App\Models\Relationship;
use Costa\Modules\Payment\PaymentEntity;
use Costa\Modules\Payment\Repository\PaymentRepositoryInterface;
use Costa\Modules\Payment\Shareds\Enums\Type;
use Costa\Shareds\Contracts\EntityInterface;
use Costa\Shareds\ValueObjects\Input\InputValueObject;
use Costa\Shareds\ValueObjects\ModelObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function __construct(
        private Payment $model,
        private Charge $charge,
        private Relationship $relationship,
        private Account $account,
        private Bank $bank,
    ) {
        //
    }

    public function find(string $uuid): EntityInterface
    {
        return $this->toEntity($this->model->where('uuid', $uuid)->firstOrFail());
    }

    public function insert(PaymentEntity $entity): EntityInterface
    {
        $objCharge = $this->charge->where('uuid', $entity->charge->id)->first();
        $objRelationship = $this->relationship->where('uuid', $entity->relationship->id)->first();
        $idBank = $entity->bank ? $this->bank->where('uuid', $entity->bank)->first()->id : null;
        $idAccount = $this->account->where('model_id', $entity->account)->first()->id;

        $data = [
            'charge_id' => $objCharge->id,
            'bank_id' => null,
            'relationship_id' => $objRelationship->id,
            'date_schedule' => $entity->schedule->format('Y-m-d'),
            'value_transaction' => $entity->value->value,
            'value_payment' => $entity->value->value,
            'completed' => $entity->completed,
            'type' => $entity->type->value,
            'uuid' => $entity->id(),
            'account_id' => $idAccount,
            'bank_id' => $idBank,
        ];

        return $this->toEntity(
            $this->model->create($data),
            $entity->charge,
            $entity->relationship,
            $entity->account,
            $entity->bank
        );
    }

    public function update(PaymentEntity $entity): EntityInterface
    {
        $obj = $this->model->where('uuid', $entity->id())->firstOrFail();
        $obj->update(['completed' => $entity->completed]);

        return $this->toEntity($obj);
    }

    private function toEntity(
        object $data,
        $objCharge = null,
        $objRelationship = null,
        $objAccount = null,
        $objBank = null,
    ) {
        
        $objCharge = $objCharge ?: new ModelObject(
            type: $type = $this->charge->find($data->charge_id),
            id: $type->id
        );

        $objRelationship = $objRelationship ?: new ModelObject(
            type: $type = $this->relationship->find($data->relationship_id),
            id: $type->id
        );

        $objAccount = $objAccount ?: $this->getAccountEntity($data->account_id);
        $objBank = $objBank ?: ($data->bank_id ? $this->getBankEntity($this->bank->find($data->bank_id)->id) : null);

        return new PaymentEntity(
            relationship: $objRelationship,
            charge: $objCharge,
            value: new InputValueObject($data->value_payment),
            schedule: new DateTime($data->date_schedule),
            id: new UuidObject($data->uuid),
            type: Type::from($data->type),
            createdAt: new DateTime($data->created_at),
            account: $objAccount,
            bank: $objBank
        );
    }

    private function getAccountEntity(int $id)
    {
        $obj = $this->account->find($id);
        return new UuidObject($obj->model_id);
    }

    private function getBankEntity(int $id)
    {
        $obj = $this->bank->find($id);
        return new UuidObject($obj->uuid);
    }
}
