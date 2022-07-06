<?php

namespace Database\Factories;

use App\Models\Relationship;
use App\Models\Tenant;
use Core\Application\Payment\Shared\Enums\PaymentTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
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
            'value' => rand(100, 1000),
            'status' => 1,
            'type' => $this->faker->randomElement(PaymentTypeEnum::toArray()),
            'relationship_id' => Relationship::factory(),
            'relationship_type' => 'Relationship',
            'date' => $this->faker->dateTime(),
            'title' => $this->faker->name(),
            'resume' => $this->faker->name(),
            'relationship_name' => $this->faker->name(),
        ];
    }
}
