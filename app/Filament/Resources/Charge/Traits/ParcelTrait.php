<?php

namespace App\Filament\Resources\Charge\Traits;

use App\Models\Enum\Charge\ParcelEnum;
use App\Models\Enum\Charge\TypeEnum;
use Carbon\Carbon;

trait ParcelTrait
{
    public function generateParcel(
        float $value,
        ParcelEnum $type,
        int $quantityParcel,
        Carbon $date,
        ?string $description
    ): array {
        $calculate = $value;
        $description = $description ?: __("Outros");

        if ($type == ParcelEnum::TOTAL) {
            $calculate = $calculate / $quantityParcel;
        }

        $charges = [];
        $rest = 0;
        $dateStart = $date;
        $dayStart = $dateStart->format('d');

        for ($i = 0; $i < $quantityParcel; $i++) {
            $response = [
                'is_parcel' => true,
                'type' => TypeEnum::UNIQUE,
                'value' => str()->truncate($calculate),
                'description' => $description . " " . ($i + 1) . '/' . $quantityParcel,
            ];

            $rest = str()->truncate($rest) + str()->truncate($calculate);

            $month = clone $dateStart;
            $dateCharge = clone $dateStart;

            $month->firstOfMonth()->addMonth($i);
            $dateCharge->firstOfMonth()->addMonth($i)->setDay($dayStart);

            if ($dateCharge->format('Ym') != $month->format('Ym')) {
                $dateCharge->subMonth()->lastOfMonth();
            }

            $response['due_date'] = $dateCharge->format('Y-m-d');

            $charges[] = $response;
        }

        do {
            $charges[count($charges) - 1]['value'] = str()->truncate($charges[count($charges) - 1]['value'] + 0.01);
            $rest += 0.01;
        } while ($rest < $value);

        return $charges;
    }
}
