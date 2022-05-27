<?php

namespace Costa\Modules\Relationship\UseCases\Supplier;

use Costa\Modules\Relationship\Repository\SupplierRepositoryInterface;
use Costa\Shareds\ValueObjects\DeleteObject;

class SupplierDeleteUseCase
{
    public function __construct(private SupplierRepositoryInterface $repo)
    {
        //
    }

    public function exec(DTO\Find\Input $input): DeleteObject
    {
        return new DeleteObject(success: $this->repo->delete($input->id));
    }
}
