<?php

namespace Costa\Modules\Charge\Payment\UseCases;

use Costa\Modules\Charge\Payment\Entity\ChargeEntity;
use Costa\Modules\Charge\Payment\Repository\ChargeRepositoryInterface;
use Costa\Modules\Relationship\Supplier\Repository\SupplierRepositoryInterface;

class FindUseCase
{
    public function __construct(
        protected ChargeRepositoryInterface $repo,
        protected SupplierRepositoryInterface $relationship,
    ) {
        //
    }

    public function handle(DTO\Find\Input $input): DTO\Find\Output
    {
        /** @var ChargeEntity */
        $objEntity = $this->repo->find($input->id);

        $objRelationship = $this->relationship->find($objEntity->supplier->id);

        return new DTO\Find\Output(
            id: $objEntity->id,
            title: $objEntity->title->value,
            description: $objEntity->description->value,
            value: $objEntity->value->value,
            supplierId: $objEntity->supplier->id,
            recurrenceId: $objEntity->recurrence,
            pay: $objEntity->payValue->value,
            date: $objEntity->date->format('Y-m-d'),
            supplierName: $objRelationship->name->value,
        );
    }
}
