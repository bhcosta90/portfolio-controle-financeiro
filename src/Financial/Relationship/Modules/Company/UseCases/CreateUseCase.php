<?php

namespace Core\Financial\Relationship\Modules\Company\UseCases;

use Core\Financial\Account\Domain\AccountEntity;
use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Financial\Relationship\Modules\Company\Repository\CompanyRepositoryInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\ValueObjects\EntityObject;
use Throwable;

class CreateUseCase
{
    public function __construct(
        private CompanyRepositoryInterface $repo,
        private AccountRepositoryInterface $account,
        private TransactionInterface $transaction,
    ) {
        //
    }

    public function handle(DTO\Create\CreateInput $input): DTO\Create\CreateOutput
    {
        $obj = CompanyEntity::create(
            name: $input->name,
            document_type: $input->document_type,
            document_value: $input->document_value,
        );

        $account = AccountEntity::create(
            entity: new EntityObject($obj->id(), $obj, $obj->name->value),
        );

        try {
            $this->repo->insert($obj);
            $this->account->insert($account);
            $this->transaction->commit();

            return new DTO\Create\CreateOutput(
                id: $obj->id(),
                name: $obj->name->value,
                account: $account->id(),
                document_type: $obj->document?->type->value,
                document_value: $obj->document?->document,
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
