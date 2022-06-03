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

class UpdateUseCase
{
    public function __construct(
        protected BankRepositoryInterface $repo,
        protected AccountRepositoryInterface $account,
        protected TransactionContract $transaction,
    ) {
        //
    }

    public function handle(DTO\Update\Input $input): DTO\Update\Output
    {
        /** @var BankEntity */
        $objEntity = $this->repo->find($input->id);
        $objEntity->update(
            name: new InputNameObject($input->name),
        );

        $objAccount = $this->account->findByEntity(new ModelObject($objEntity->id(), $objEntity));
        $objAccount->update($input->value);

        try {
            $this->repo->update($objEntity);
            $this->account->update($objAccount);
            $this->transaction->commit();

            return new DTO\Update\Output(
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
