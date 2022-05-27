<?php

namespace Database\Factories;

use App\Http\Controllers\Admin\Charge\ChargeController;
use App\Models\Relationship;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Charge>
 */
class ChargeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $class = [
            ChargeController::class,
        ];

        return [
            'relationship_id' => Relationship::factory()->create(),
            'uuid' => str()->uuid(),
            'base' => str()->uuid(),
            'title' => $this->faker->sentence(3),
            'model' => $this->faker->randomElement($class),
            'date_start' => $this->faker->date(),
            'date_finish' => $this->faker->date(),
            'date_due' => $this->faker->date(),
            'type' => 1,
            'status' => 1,
            'value_charge' => rand(100, 999),
        ];
    }
}
