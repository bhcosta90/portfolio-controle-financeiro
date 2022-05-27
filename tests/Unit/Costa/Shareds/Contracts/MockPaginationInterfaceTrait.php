<?php

namespace Tests\Unit\Costa\Shareds\Contracts;

use Costa\Shareds\Contracts\PaginationInterface;
use Mockery;

trait MockPaginationInterfaceTrait
{
    /**
     * @return \Mockery\MockInterface|PaginationInterface
     */
    public function mockPaginationInterface(array $items = [])
    {
        /** @var Mockery\MockInterface */
        $pagination = Mockery::mock(stdClass::class, PaginationInterface::class);
        $pagination->shouldReceive('items')->andReturn($items);
        $pagination->shouldReceive('total')->andReturn(0);
        $pagination->shouldReceive('firstPage')->andReturn(0);
        $pagination->shouldReceive('lastPage')->andReturn(0);
        $pagination->shouldReceive('perPage')->andReturn(0);
        $pagination->shouldReceive('to')->andReturn(0);
        $pagination->shouldReceive('from')->andReturn(0);
        $pagination->shouldReceive('currentPage')->andReturn(0);
        $pagination->shouldReceive('getValueTotal')->andReturn(0);

        return $pagination;
    }
}
