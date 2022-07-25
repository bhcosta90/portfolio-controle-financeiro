<?php

namespace Core\Application\Transaction\UseCases;

use Core\Application\BankAccount\Modules\Account\Repository\AccountRepository;
use Core\Application\Transaction\Domain\TransactionEntity;
use Core\Application\Transaction\Repository\TransactionRepository;
use Core\Application\Transaction\Shared\Enums\TransactionTypeEnum;
use Core\Shared\Interfaces\TransactionInterface;
use Throwable;

class ExecuteUseCase
{
    public function __construct(
        private TransactionRepository $repository,
        private TransactionInterface $transaction,
        private AccountRepository $account,
    ) {
        //
    }

    public function handle(DTO\Execute\Input $input): DTO\Execute\Output
    {
        /** @var TransactionEntity */
        $objTransaction = $this->repository->find($input->id);
        try {
            if ($objTransaction->type == TransactionTypeEnum::CREDIT) {
                $this->account->addValue((string) $objTransaction->accountTo, $objTransaction->value->value);
            } else {
                $this->account->subValue((string) $objTransaction->accountTo, $objTransaction->value->value);
            }

            $objTransaction->completed();
            $ret = $this->repository->update($objTransaction);
            $this->transaction->commit();
            return new DTO\Execute\Output($ret);
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
