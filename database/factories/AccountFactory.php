<?php

namespace Database\Factories;

use App\Models\Relationship;
use Costa\Financeiro\Relationship\Modules\Customer\Entities\CustomerEntity;
use Costa\Financeiro\Relationship\Modules\Supplier\Entities\SupplierEntity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
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
            'model_type' => $this->faker->randomElement($class),
            'model_id' => str()->uuid(),
        ];
    }
}
