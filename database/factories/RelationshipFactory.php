<?php

namespace Database\Factories;

use Costa\Financeiro\Relationship\Modules\Customer\Entities\CustomerEntity;
use Costa\Financeiro\Relationship\Modules\Supplier\Entities\SupplierEntity;
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
        $class = [
            CustomerEntity::class,
            SupplierEntity::class,
        ];
        
        return [
            'uuid' => str()->uuid(),
            'name' => $this->faker->name,
            'model' => $this->faker->randomElement($class),
        ];
    }
}
