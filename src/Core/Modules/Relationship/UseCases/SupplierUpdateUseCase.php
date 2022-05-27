<?php

namespace Costa\Modules\Relationship\UseCases;

use Costa\Modules\Relationship\Repositories\SupplierRepositoryInterface;

class SupplierUpdateUseCase extends SupplierCreateUseCase
{
    public function __construct(
        protected SupplierRepositoryInterface $relationship
    ) {
        //
    }
}
