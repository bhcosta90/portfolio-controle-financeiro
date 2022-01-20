<?php

namespace Tests\Unit\Models;

use App\Models\FormPayment as Model;
use PHPUnit\Framework\TestCase;

class FormPaymentUnitTest extends TestCase
{
    private $model;

    protected function setUp(): void
    {
        /** @var Model $model */
        $this->model = new Model;
    }

    public function testFillable()
    {
        $array = [
            'user_id',
            'type',
            'sync_data',
        ];

        $this->assertEqualsCanonicalizing($array, $this->model->getFillable());
    }

    public function testIfUseTraits()
    {
        $array = [
            \Prettus\Repository\Traits\TransformableTrait::class,
            \Costa\LaravelPackage\Traits\Models\UuidGenerate::class,
            \Illuminate\Database\Eloquent\Factories\HasFactory::class,
            \Illuminate\Database\Eloquent\SoftDeletes::class,
        ];

        $modelTraits = array_keys(class_uses(Model::class));
        $this->assertEqualsCanonicalizing($array, $modelTraits);
    }

    public function testCasts()
    {
        $array = [
            'deleted_at' => 'datetime',
        ];

        $this->assertEqualsCanonicalizing($array, $this->model->getCasts());
    }
}
