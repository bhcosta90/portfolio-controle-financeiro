<?php

namespace Costa\Modules\Relationship\Supplier\UseCases;

use Costa\Modules\Relationship\Supplier\Entity\SupplierEntity;
use Costa\Modules\Relationship\Supplier\Repository\SupplierRepositoryInterface;

class FindUseCase
{
    public function __construct(
        protected SupplierRepositoryInterface $relationship
    ) {
        //
    }

    public function handle(DTO\Find\Input $input): DTO\Find\Output
    {
        /** @var SupplierEntity */
        $objEntity = $this->relationship->find($input->id);
        return new DTO\Find\Output(
            id: $objEntity->id,
            name: $objEntity->name->value,
            document_type: $objEntity->document?->type->value,
            document_value: $objEntity->document?->document,
        );
    }
}
