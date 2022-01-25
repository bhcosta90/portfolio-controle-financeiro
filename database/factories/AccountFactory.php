<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->lastName(),
            'value' => 0,
            'bank_code' => '0000',
            'bank_agency' => time(),
            'bank_account' => time(),
            'bank_digit' => $this->faker->numberBetween(0, 9),
        ];
    }
}
