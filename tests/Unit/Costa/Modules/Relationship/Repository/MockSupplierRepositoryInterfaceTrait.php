<?php

namespace Tests\Unit\Costa\Modules\Relationship\Repository;

use Costa\Modules\Relationship\Repository\SupplierRepositoryInterface;
use Mockery;

trait MockSupplierRepositoryInterfaceTrait
{
    /**
     * @return \Mockery\MockInterface|SupplierRepositoryInterface
     */
    public function mockSupplierRepositoryInterface()
    {
        return Mockery::mock(stdClass::class, SupplierRepositoryInterface::class);
    }
}
