<?php

namespace Core\Financial\Charge\Modules\Payment\UseCases;

use Core\Financial\Charge\Modules\Payment\Repository\PaymentRepositoryInterface;
use Core\Financial\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Financial\Charge\Shared\Enums\ChargeTypeEnum;
use Core\Financial\Recurrence\Repository\RecurrenceRepositoryInterface;
use Core\Financial\Relationship\Modules\Company\Repository\CompanyRepositoryInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\Support\ParcelCalculate;
use Core\Shared\Support\DTO\ParcelCalculate\Input as ParcelCalculateInput;
use DateTime;

class CreateUseCase
{
    public function __construct(
        private PaymentRepositoryInterface $repo,
        private CompanyRepositoryInterface $company,
        private RecurrenceRepositoryInterface $recurrence,
        private TransactionInterface $transaction,
    ) {
        //
    }

    /**
     * @param DTO\Create\CreateInput $input
     * @return DTO\Create\CreateOutput[]
     */
    public function handle(DTO\Create\CreateInput $input): array
    {
        $ret = [];
        $objCompany = $this->company->find($input->companyId);
        $objRecurrence = $input->recurrenceId ? $this->recurrence->find($input->recurrenceId) : null;

        $objParcels = new ParcelCalculate();
        $dataParcels = $objParcels->handle(new ParcelCalculateInput(
            $input->parcels,
            $input->value,
            new DateTime($input->date)
        ));
        foreach($dataParcels as $data){
            $objEntity = Entity::create(
                $input->groupId,
                $data->value,
                $objCompany,
                ChargeTypeEnum::CREDIT->value,
                $data->date->format('Y-m-d'),
                $objRecurrence,
            );
            $this->repo->insert($objEntity);
            $ret[] = new DTO\Create\CreateOutput(
                $objEntity->id(),
                $input->groupId,
                $data->value,
                $objEntity->date->format('Y-m-d'),
                $input->companyId,
                $input->recurrenceId,
            );
        }

        return $ret;
    }
}