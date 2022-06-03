<?php

namespace Costa\Modules\Bank\UseCases;

use Costa\Modules\Account\Entity\AccountEntity;
use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Bank\Entity\BankEntity;
use Costa\Modules\Bank\Repository\BankRepositoryInterface;
use Costa\Shared\Contracts\TransactionContract;
use Costa\Shared\ValueObject\Input\InputNameObject;
use Costa\Shared\ValueObject\ModelObject;
use Throwable;

class CreateUseCase
{
    public function __construct(
        protected BankRepositoryInterface $repo,
        protected AccountRepositoryInterface $account,
        protected TransactionContract $transaction,
    ) {
        //
    }

    public function handle(DTO\Create\Input $input): DTO\Create\Output
    {
        $objEntity = new BankEntity(
            name: new InputNameObject($input->name),
        );

        $objAccount = new AccountEntity(
            entity: new ModelObject($objEntity->id(), $objEntity),
            value: $input->value,
        );

        try {
            $this->repo->insert($objEntity);
            $this->account->insert($objAccount);
            $this->transaction->commit();

            return new DTO\Create\Output(
                id: $objEntity->id,
                name: $objEntity->name->value,
                value: $objAccount->value,
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
