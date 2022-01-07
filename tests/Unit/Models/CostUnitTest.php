<?php

namespace Tests\Unit\Models;

use App\Models\Cost as Model;
use PHPUnit\Framework\TestCase;

class CostUnitTest extends TestCase
{
    private $model;

    protected function setUp(): void
    {
        /** @var Model $model */
        $this->model = new Model;
    }

    public function testFillable()
    {
        $array = [];

        $this->assertEqualsCanonicalizing($array, $this->model->getFillable());
    }

    public function testIfUseTraits()
    {
        $array = [
            \Illuminate\Database\Eloquent\Factories\HasFactory::class,
        ];

        $modelTraits = array_keys(class_uses(Model::class));
        $this->assertEqualsCanonicalizing($array, $modelTraits);
    }
}
