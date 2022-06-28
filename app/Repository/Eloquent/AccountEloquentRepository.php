<?php

namespace App\Repository\Eloquent;

use App\Models\Account;
use Core\Financial\Account\Contracts\AccountInterface;
use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Account\Domain\AccountEntity;
use Core\Financial\BankAccount\Domain\BankAccountEntity;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface;
use Core\Financial\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Financial\Relationship\Modules\Company\Repository\CompanyRepositoryInterface;
use Core\Financial\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Financial\Relationship\Modules\Customer\Repository\CustomerRepositoryInterface;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\EntityObject;
use Exception;

class AccountEloquentRepository implements AccountRepositoryInterface
{
    public function __construct(
        protected Account $model,
        protected CompanyRepositoryInterface $company,
        protected CustomerRepositoryInterface $customer,
        protected BankAccountRepositoryInterface $bank,
    ) {
        //  
    }

    public function insert(AccountEntity $entity): bool
    {
        return (bool) $this->model->create([
            'id' => $entity->id(),
            'value' => $entity->value,
            'entity_id' => $entity->entity->id,
            'entity_type' => $entity->entity->type,
        ]);
    }

    public function find(string $id, string $entity): AccountEntity
    {
        $obj = $this->model->where('entity_id', $id)->where('entity_type', $entity)->first();
        $entity = $this->getEntity($obj->entity_type, $obj->entity_id);
        return $this->entity($obj, $entity);
    }

    public function delete(AccountEntity $entity): bool
    {
        return $this->model->find($entity->id())->delete();
    }

    /**
     * @param object $input
     * @param EntityAbstract $entity
     * @return AccountEntity
     */
    public function entity(object $input): AccountEntity
    {
        return AccountEntity::create(
            entity: new EntityObject($input->entity_id, $input->entity_type),
            value: $input->value,
            id: $input->id,
            createdAt: $input->create_at,
        );
    }

    public function add(AccountEntity $account, float $value): bool
    {
        $obj = $this->model->find($account->id());
        $obj->increment('value', (float) abs($value));
        return (bool) $obj;
    }

    public function sub(AccountEntity $account, float $value): bool
    {
        $obj = $this->model->find($account->id());
        $obj->decrement('value', (float) abs($value));
        return (bool) $obj;
    }

    /** @return EntityAbstract */
    private function getEntity($entityType, $entityId): EntityAbstract
    {
        return match ($entityType) {
            CustomerEntity::class => $this->customer->find($entityId),
            CompanyEntity::class => $this->company->find($entityId),
            BankAccountEntity::class => $this->bank->find($entityId),
            default => throw new Exception($entityType . " do not implemented"),
        };
    }
}
