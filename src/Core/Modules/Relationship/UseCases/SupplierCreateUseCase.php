<?php

namespace Costa\Modules\Relationship\UseCases;

use Costa\Modules\Relationship\Repositories\CustomerRepositoryInterface;
use Costa\Modules\Relationship\SupplierEntity;

class SupplierCreateUseCase extends CustomerCreateUseCase
{
    public function __construct(
        private CustomerRepositoryInterface $relationship
    ) {
        //
    }

    protected function getObject()
    {
        return SupplierEntity::class;
    }
}
