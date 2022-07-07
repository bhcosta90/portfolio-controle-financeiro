<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccountBank>
 */
class AccountBankFactory extends Factory
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
            'tenant_id' => Tenant::factory(),
            'name' => $this->faker->company(),
            'value' => rand(0, 100) < 80 ? (rand(10000, 100000) / 100) : 0,
        ];
    }
}
