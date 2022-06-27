<?php

namespace Core\Financial\Charge\Modules\Payment\UseCases;

use Core\Financial\Charge\Modules\Payment\Repository\PaymentRepositoryInterface;
use Core\Financial\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Financial\Charge\Shared\Enums\ChargeTypeEnum;
use Core\Financial\Relationship\Modules\Company\Repository\CompanyRepositoryInterface;
use Core\Shared\Interfaces\TransactionInterface;

class CreateUseCase
{
    public function __construct(
        private PaymentRepositoryInterface $repo,
        private CompanyRepositoryInterface $company,
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
            $objCompany = $this->company->find($input->companyId);
            $objEntity = Entity::create($input->groupId, $input->value, $objCompany, ChargeTypeEnum::CREDIT->value);
            $this->repo->insert($objEntity);
            $ret[] = new DTO\Create\CreateOutput($objEntity->id(), $input->groupId, $input->value, $input->companyId);
        }

        return $ret;
    }
}
