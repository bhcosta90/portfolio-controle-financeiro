<?php

namespace Core\Application\AccountBank\Services;

use Core\Application\AccountBank\Repository\AccountBankRepository as Repo;
use Core\Application\AccountBank\Services\DTO\Transfer\{Input, Output};
use Core\Application\Payment\Domain\PaymentEntity;
use Core\Application\Payment\Repository\PaymentRepository;
use Core\Application\Payment\Shared\Enums\PaymentTypeEnum;
use Core\Shared\Interfaces\TransactionInterface;
use DateTime;
use Exception;
use Throwable;

class TransferService
{
    public function __construct(
        private Repo $repository,
        private PaymentRepository $payment,
        private TransactionInterface $transaction,
    ) {
        //
    }

    public function handle(Input $input): Output
    {
        if ($input->idBankTo == $input->idBankFrom) {
            throw new Exception('A transfer to the same bank cannot occur');
        }

        $objBankFrom = $this->repository->find($input->idBankFrom);

        try {
            $objPayment = PaymentEntity::create(
                tenant: $input->tenant,
                relationship: null,
                charge: null,
                title: "Transferência - Saque",
                resume: null,
                name: null,
                bank: $input->idBankFrom,
                value: $input->value,
                status: null,
                type: PaymentTypeEnum::DEBIT->value,
                date: (new DateTime())->format('Y-m-d H:i:s'),
            );

            $objReceive = PaymentEntity::create(
                tenant: $input->tenant,
                relationship: null,
                charge: null,
                title: "Transferência - Depósito",
                resume: null,
                name: null,
                bank: $input->idBankTo,
                value: $input->value,
                status: null,
                type: PaymentTypeEnum::CREDIT->value,
                date: (new DateTime())->format('Y-m-d H:i:s'),
            );
            
            $this->payment->insert($objPayment);
            $this->payment->insert($objReceive);

            $this->transaction->commit();
            return new Output(
                idPayment: $objPayment->id(),
                idReceive: $objReceive->id(),
            );
        } catch(Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
