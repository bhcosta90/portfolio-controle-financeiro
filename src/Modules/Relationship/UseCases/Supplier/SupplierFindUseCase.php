<?php

namespace Costa\Modules\Relationship\UseCases\Supplier;

use Costa\Modules\Relationship\Repository\SupplierRepositoryInterface;

class SupplierFindUseCase
{
    public function __construct(
        private SupplierRepositoryInterface $repo,
    ) {
        //
    }

    public function exec(DTO\Find\Input $input): DTO\Find\Output
    {
        $obj = $this->repo->find($input->id);

        return new DTO\Find\Output(
            name: $obj->name->value,
            id: $obj->id(),
        );
    }
}
