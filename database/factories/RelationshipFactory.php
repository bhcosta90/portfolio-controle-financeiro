<?php

namespace Database\Factories;

use App\Models\Tenant;
use Core\Financial\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Financial\Relationship\Modules\Customer\Domain\CustomerEntity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Relationship>
 */
class RelationshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => str()->uuid(),
            'entity' => $this->faker->randomElement([
                CompanyEntity::class,
                CustomerEntity::class,
            ]),
            'name' => $this->faker->name(),
            'tenant_id' => Tenant::factory(),
            'document_type' => 2,
            'document_value' => preg_replace('/[^0-9]/', '', $this->faker->cnpj())
        ];
    }
}
