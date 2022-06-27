<?php

namespace Core\Financial\Charge\Modules\Receive\UseCases;

use Core\Financial\Charge\Modules\Receive\Repository\ReceiveRepositoryInterface;
use Core\Financial\Relationship\Modules\Customer\Repository\CustomerRepositoryInterface;
use Core\Financial\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Financial\Charge\Shared\Enums\ChargeTypeEnum;
use Core\Shared\Interfaces\TransactionInterface;

class CreateUseCase
{
    public function __construct(
        private ReceiveRepositoryInterface $repo,
        private CustomerRepositoryInterface $customer,
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

        for ($i = 0; $i < $input->parcels; $i++) {
            $objCustomer = $this->customer->find($input->customerId);
            $objEntity = Entity::create($input->groupId, $input->value, $objCustomer, ChargeTypeEnum::CREDIT->value);
            $this->repo->insert($objEntity);
            $ret[] = new DTO\Create\CreateOutput($objEntity->id(), $input->groupId, $input->value, $input->customerId);
        }

        return $ret;
    }
}
