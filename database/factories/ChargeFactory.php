<?php

namespace Database\Factories;

use App\Models\Relationship;
use App\Models\Tenant;
use Core\Financial\Charge\Modules\Payment\Domain\PaymentEntity;
use Core\Financial\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Financial\Charge\Shared\Enums\ChargeStatusEnum;
use Core\Financial\Charge\Shared\Enums\ChargeTypeEnum;
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
        $relationship = Relationship::factory()->create();

        return [
            'id' => str()->uuid(),
            'tenant_id' => Tenant::factory(),
            'relationship_id' => $relationship->id,
            'relationship_type' => $relationship->entity,
            'entity' => $this->faker->randomElement([
                ReceiveEntity::class,
                PaymentEntity::class,
            ]),
            'group_id' => str()->uuid(),
            'status' => ChargeStatusEnum::PENDING->value,
            'type' => $this->faker->randomElement(ChargeTypeEnum::toArray()),
            'value_charge' => rand(100, 1000),
            'date' => date('Y-m-d'),
        ];
    }
}
