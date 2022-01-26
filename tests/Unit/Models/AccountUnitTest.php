<?php

namespace Tests\Unit\Models;

use App\Models\Account as Model;
use PHPUnit\Framework\TestCase;

class AccountUnitTest extends TestCase
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
            'name',
            'value',
            'type',
            'bank_code',
            'bank_account',
            'bank_digit',
            'bank_agency',
        ];

        $this->assertEqualsCanonicalizing($array, $this->model->getFillable());
    }

    public function testIfUseTraits()
    {
        $array = [
            \Costa\LaravelPackage\Traits\Models\UuidGenerate::class,
            \Illuminate\Database\Eloquent\Factories\HasFactory::class,
            \Illuminate\Database\Eloquent\SoftDeletes::class,
            \Prettus\Repository\Traits\TransformableTrait::class,
            \Venturecraft\Revisionable\RevisionableTrait::class,
        ];

        $modelTraits = array_keys(class_uses(Model::class));
        $this->assertEqualsCanonicalizing($array, $modelTraits);
    }

    public function testCasts()
    {
        $array = [
            'value' => 'float',
            'can_deleted' => 'boolean',
            'deleted_at' => 'datetime',
        ];

        $this->assertEqualsCanonicalizing($array, $this->model->getCasts());
    }
}
