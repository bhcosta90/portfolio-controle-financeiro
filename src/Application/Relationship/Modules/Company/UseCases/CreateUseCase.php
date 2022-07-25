<?php

namespace Core\Application\Relationship\Modules\Company\UseCases;

use Core\Application\BankAccount\Modules\Account\Repository\AccountRepository;
use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity as Entity;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository as Repo;
use Core\Shared\Interfaces\TransactionInterface;
use Throwable;

class CreateUseCase
{
    public function __construct(
        private Repo $repository,
        private AccountRepository $account,
        private TransactionInterface $transaction,
    ) {
        //
    }

    public function handle(DTO\Create\Input $input): DTO\Create\Output
    {
        $entity = Entity::create(tenant: $input->tenant, name: $input->name);
        try {
            $this->repository->insert($entity);
            $this->account->insert($entity->account);
            $this->transaction->commit();
            return new DTO\Create\Output(
                name: $entity->name->value,
                id: $entity->id(),
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
