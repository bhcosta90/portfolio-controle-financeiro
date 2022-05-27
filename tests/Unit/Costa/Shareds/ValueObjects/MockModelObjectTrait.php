<?php

namespace Tests\Unit\Costa\Shareds\ValueObjects;

use Costa\Shareds\ValueObjects\ModelObject;
use Mockery;
use stdClass;

trait MockModelObjectTrait
{
    /** @return Mockery\MockInterface|ModelObject */
    public function mockModelObject($id =1, $value = 'testing')
    {
        return Mockery::mock(stdClass::class, ModelObject::class, [$id, $value]);
    }
}
