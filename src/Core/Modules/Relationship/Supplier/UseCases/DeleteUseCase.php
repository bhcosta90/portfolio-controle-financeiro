<?php

namespace Costa\Modules\Relationship\Supplier\UseCases;

use Costa\Modules\Relationship\Supplier\Entity\SupplierEntity;
use Costa\Modules\Relationship\Supplier\Repository\SupplierRepositoryInterface;
use Costa\Shared\ValueObject\DeleteObject;

class DeleteUseCase
{
    public function __construct(
        protected SupplierRepositoryInterface $relationship
    ) {
        //
    }

    public function handle(DTO\Find\Input $input): DeleteObject
    {
        /** @var SupplierEntity */
        $objEntity = $this->relationship->find($input->id);
        return new DeleteObject($this->relationship->delete($objEntity));
    }
}
