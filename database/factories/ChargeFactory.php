<?php

namespace Database\Factories;

use App\Models\Charge;
use App\Models\Income;
use App\Models\Cost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChargeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' =>  User::factory()->create(),
            'value' => rand(100, 200),
            'customer_name' => $this->faker->lastName(),
            'due_date' => $this->faker->date(),
            'resume' => $this->faker->sentence(1),
            'description' => $this->faker->sentence(6),
            'last_date' => null,
            'parcel_actual' => 0,
            'parcel_total' => 0,
            'type' => $this->faker->randomElement(array_keys(Charge::$typeOptions)),
            'status' => $this->faker->randomElement(array_keys(Charge::$statusOptions)),
        ];
    }
}
