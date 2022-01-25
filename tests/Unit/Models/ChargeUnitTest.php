<?php

namespace Tests\Unit\Models;

use App\Models\Charge as Model;
use PHPUnit\Framework\TestCase;

class ChargeUnitTest extends TestCase
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
            'chargeable',
            'value',
            'value_recursive',
            'value_pay',
            'customer_name',
            'due_date',
            'last_date',
            'parcel_actual',
            'parcel_total',
            'type',
            'status',
            'future',
            'description',
            'resume',
        ];

        $this->assertEqualsCanonicalizing($array, $this->model->getFillable());
    }

    public function testIfUseTraits()
    {
        $array = [
            \Costa\LaravelPackage\Traits\Models\UuidGenerate::class,
            \Illuminate\Database\Eloquent\Factories\HasFactory::class,
            \Illuminate\Database\Eloquent\SoftDeletes::class,
            \Venturecraft\Revisionable\RevisionableTrait::class,
        ];

        $modelTraits = array_keys(class_uses(Model::class));
        $this->assertEqualsCanonicalizing($array, $modelTraits);
    }

    public function testCasts()
    {
        $array = [
            'value' => 'float',
            'value_recursive' => 'float',
            'parcel_actual' => 'integer',
            'parcel_total' => 'integer',
            'deleted_at' => 'datetime',
            'future' => 'boolean',
        ];

        $this->assertEqualsCanonicalizing($array, $this->model->getCasts());
    }
}
