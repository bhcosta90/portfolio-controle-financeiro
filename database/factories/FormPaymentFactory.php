<?php

namespace Database\Factories;

use App\Models\FormPayment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormPaymentFactory extends Factory
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
            'type' => $this->faker->randomElement(FormPayment::TYPES_FORM_PAYMENT),
            'active' => false,
        ];
    }
}
