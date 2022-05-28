<?php

namespace Costa\Modules\Charge\Utils\Shared;

use Exception;

class ParcelCalculate
{
    /** @return DTO\ParcelCalculate\Output[] */
    public function handle(DTO\ParcelCalculate\Input $input): array
    {
        $ret = [];
        $totalValue = 0;
        $valueCharge = $this->truncate($input->value / $input->total);
        $inputValue = $this->truncate($input->value);

        for ($i = 0; $i < $input->total; $i++) {

            $verifyDate = clone $input->date;
            $verifyDate->modify('first day of this month');
            $verifyDate->modify('+' . $i . ' month');

            $dateParcel = clone $input->date;
            $dateParcel->modify('+' . $i . ' month');

            if ($verifyDate->format('Ym') != $dateParcel->format('Ym')) {
                $verifyDate->modify('last day of this month');
                $dateParcel = $verifyDate;
            }

            $totalValue += $this->truncate($valueCharge);

            $transactions[] = [
                'date' => $dateParcel,
                'value' => $this->truncate($valueCharge)
            ];
        }

        if ($totalValue < $inputValue) {
            $calc = $inputValue - $totalValue;
            $transactions[$input->total - 1]['value'] = $this->truncate($transactions[$input->total - 1]['value'] + $calc);
            $totalValue = $totalValue + $calc;
        } elseif ($totalValue > $inputValue) {
            throw new Exception('Favor calcular esse valor com o total de parcelas: ' . $input->total . ', com o valor de ' . $input->value);
        }

        foreach($transactions as $rs){
            $ret[] = new DTO\ParcelCalculate\Output(date: $rs['date'], value: $rs['value']);
        }

        return $ret;
    }

    private function truncate($value, $precision = 2)
    {
        return intval($value * ($p = pow(10, $precision))) / $p;
    }
}
