<?php

namespace Core\Application\BankAccount\Modules\Bank\UseCases;

use Core\Application\BankAccount\Modules\Account\Repository\AccountRepository;
use Core\Application\BankAccount\Modules\Bank\Domain\BankEntity as Entity;
use Core\Application\BankAccount\Modules\Bank\Repository\BankRepository as Repository;
use Core\Shared\Interfaces\TransactionInterface;
use Throwable;

class CreateUseCase
{
    public function __construct(
        private TransactionInterface $transaction,
        private Repository $repository,
        private AccountRepository $account,
    ) {
        //
    }

    public function handle(DTO\Create\Input $input): DTO\Create\Output
    {
        $objEntity = Entity::create(
            $input->tenant,
            $input->name,
            $input->value,
            true,
            $input->bankCode,
            $input->agency,
            $input->agencyDigit,
            $input->account,
            $input->accountDigit,
        );

        try {
            $this->repository->insert($objEntity);
            $this->account->insert($objEntity->accountEntity);
            $this->transaction->commit();
            return new DTO\Create\Output(
                $objEntity->id(),
                $input->tenant,
                $input->name,
                $input->value,
                $input->bankCode,
                $input->agency,
                $input->agencyDigit,
                $input->account,
                $input->accountDigit,
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
