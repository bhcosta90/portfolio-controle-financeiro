<?php

namespace Core\Financial\Charge\Modules\Receive\UseCases;

use Core\Financial\Charge\Modules\Receive\Repository\ReceiveRepositoryInterface;
use Core\Financial\Relationship\Modules\Customer\Repository\CustomerRepositoryInterface;
use Core\Financial\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Financial\Charge\Shared\Enums\ChargeStatusEnum;
use Core\Financial\Charge\Shared\Enums\ChargeTypeEnum;
use Core\Financial\Recurrence\Repository\RecurrenceRepositoryInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\Support\ParcelCalculate;
use Core\Shared\Support\DTO\ParcelCalculate\Input as ParcelCalculateInput;
use DateTime;
use Throwable;

class CreateUseCase
{
    public function __construct(
        private ReceiveRepositoryInterface $repo,
        private CustomerRepositoryInterface $customer,
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
        $objCustomer = $this->customer->find($input->customerId);
        $objRecurrence = $input->recurrenceId ? $this->recurrence->find($input->recurrenceId) : null;
        
        $objParcels = new ParcelCalculate();
        $dataParcels = $objParcels->handle(new ParcelCalculateInput(
            $input->parcels,
            $input->value,
            new DateTime($input->date)
        ));

        try {
            foreach ($dataParcels as $data) {
                $objEntity = Entity::create(
                    $input->groupId,
                    $data->value,
                    $objCustomer,
                    ChargeTypeEnum::DEBIT->value,
                    $data->date->format('Y-m-d'),
                    $objRecurrence,
                    ChargeStatusEnum::PENDING->value
                );
                $this->repo->insert($objEntity);
                $ret[] = new DTO\Create\CreateOutput(
                    $objEntity->id(),
                    $input->groupId,
                    $data->value,
                    $objEntity->date->format('Y-m-d'),
                    $input->customerId,
                    $input->recurrenceId,
                );
            }
            $this->transaction->commit();
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }

        return $ret;
    }
}
