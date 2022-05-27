<?php

namespace Costa\Modules\Account\UseCases\Bank;

use Costa\Modules\Account\Entities\AccountEntity;
use Costa\Modules\Account\Entities\BankEntity;
use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Account\Repository\BankRepositoryInterface;
use Costa\Shareds\Contracts\TransactionContract;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\ModelObject;
use Throwable;

class BankCreateUseCase
{
    public function __construct(
        private BankRepositoryInterface $repo,
        private AccountRepositoryInterface $account,
        private TransactionContract $transaction,
    ) {
        //
    }

    public function exec(DTO\Create\Input $input): DTO\Create\Output
    {
        $obj = new BankEntity(
            name: new InputNameObject($input->name), 
            bank: $input->bank,
            active: $input->active,
        );

        $objAccount = new AccountEntity(
            model: new ModelObject($obj->id, $obj),
            value: $input->value,
        );

        try {
            $this->repo->insert($obj);
            $this->account->insert($objAccount);
            $this->transaction->commit();

            return new DTO\Create\Output(
                name: $obj->name->value,
                bank: $obj->bank,
                value: $input->value,
                id: $obj->id(),
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
