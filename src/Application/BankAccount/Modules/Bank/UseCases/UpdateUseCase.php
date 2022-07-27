<?php

namespace Core\Application\BankAccount\Modules\Bank\UseCases;

use Core\Application\BankAccount\Modules\Account\Repository\AccountRepository;
use Core\Application\BankAccount\Modules\Bank\Domain\BankEntity;
use Core\Application\BankAccount\Modules\Bank\Repository\BankRepository as Repository;
use Core\Application\Tenant\Repository\TenantRepository;
use Core\Application\Transaction\Domain\TransactionEntity;
use Core\Application\Transaction\Repository\TransactionRepository;
use Core\Application\Transaction\Shared\Enums\TransactionStatusEnum;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\ValueObjects\UuidObject;
use Throwable;

class UpdateUseCase
{
    public function __construct(
        private TransactionInterface $transaction,
        private Repository $repository,
        private TransactionRepository $transactionRepository,
        private TenantRepository $tenant,
        private AccountRepository $account,
    ) {
        //
    }

    public function handle(DTO\Update\Input $input): DTO\Update\Output
    {
        /** @var BankEntity */
        $objEntity = $this->repository->find($input->id);
        $ret = $input->value - $objEntity->accountEntity->value;
        $objEntity->update(
            $input->name,
            $input->bankCode,
            $input->agency,
            $input->agencyDigit,
            $input->account,
            $input->accountDigit,
        );

        if ($input->active) {
            $objEntity->enable();
        } else {
            $objEntity->disable();
        }

        $objTenant = $this->tenant->find($objEntity->tenant);

        if ($ret != 0) {
            $objTransaction = TransactionEntity::create(
                $objEntity->tenant,
                UuidObject::random(),
                $ret > 0 ? 'Deposit' : "Withdrawal",
                $objEntity->accountEntity->id,
                $objTenant->account->id(),
                $objEntity->id(),
                $objEntity,
                null,
                null,
                null,
                abs($ret),
                $ret > 0 ? 1 : 2,
                date('Y-m-d'),
                TransactionStatusEnum::COMPLETE->value,
            );
        }

        try {
            $this->repository->update($objEntity);

            if (!empty($objTransaction)) {
                $this->transactionRepository->insert($objTransaction);

                if ($ret > 0) {
                    $this->account->addValue($objEntity->accountEntity->id(), abs($ret));
                } else {
                    $this->account->subValue($objEntity->accountEntity->id(), abs($ret));
                }
            }

            $this->transaction->commit();
            return new DTO\Update\Output(
                $objEntity->id(),
                $input->name,
                $input->value,
                isset($objTransaction) ? $objTransaction->id() : null,
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
