<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
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
            'group_id' => str()->uuid(),
            'status' => 1,
            'previous_value' => 0,
            'title' => $this->faker->sentence(6),
            'date' => date('Y-m-d'),
        ];
    }
}
