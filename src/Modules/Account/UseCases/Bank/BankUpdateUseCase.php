<?php

namespace Costa\Modules\Account\UseCases\Bank;

use Costa\Modules\Account\Entities\BankEntity;
use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Account\Repository\BankRepositoryInterface;
use Costa\Shareds\Contracts\TransactionContract;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\ModelObject;
use Throwable;

class BankUpdateUseCase
{
    public function __construct(
        private BankRepositoryInterface $repo,
        private AccountRepositoryInterface $account,
        private TransactionContract $transaction,
    ) {
        //
    }

    public function exec(DTO\Update\Input $input): DTO\Update\Output
    {
        /** @var BankEntity */
        $obj = $this->repo->find($input->id);
        $obj->update(
            name: new InputNameObject($input->name)
        );

        $objAccount = $this->account->find(new ModelObject($obj->id(), $obj));

        try {
            $this->repo->update($obj);

            if ($objAccount->value !== $input->value) {
                $objAccount->update(value: $input->value);
                $this->account->update($objAccount);
            }

            $this->transaction->commit();

            return new DTO\Update\Output(
                name: $obj->name->value,
                id: $obj->id(),
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
