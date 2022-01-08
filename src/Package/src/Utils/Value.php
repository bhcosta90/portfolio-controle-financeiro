<?php

namespace Costa\Package\Utils;

use Carbon\Carbon;

final class Value
{
    public function parcel(Carbon $date, float $value, int $total): array
    {
        $totalValue = 0;
        $ret = [];

        for ($i = 0; $i < $total; $i++) {
            $cloneDate = clone $date;

            $verificadorData = (new Carbon($date));
            $verificadorData->firstOfMonth()->addMonth($i);
            $dataAtual = $cloneDate->addMonth($i);

            if ($verificadorData->format('mY') != $dataAtual->format('mY')) {
                $dataAtual = $verificadorData->lastOfMonth();
            }

            $valueCalculate = $this->truncate($value / $total);
            $totalValue += $valueCalculate;
            $ret[] = [
                'value' => $valueCalculate,
                'due_date' => $dataAtual->format('Y-m-d'),
            ];
        }

        $addValue = 0;
        if ($this->truncate($totalValue) != $this->truncate($value)) {
            do {
                $totalValue += 0.01;
                $addValue += 0.01;
            } while ($this->truncate($totalValue) != $this->truncate($value));
        }
        $last = array_pop($ret);
        $ret[] = ['value' => $this->truncate($last['value']) + $addValue] + $last;

        return $ret;
    }

    public function truncate(float $valor, int $precisao = 2): float
    {
        return number_format(intval($valor * ($p = pow(10, $precisao))) / $p, $precisao);
    }
}
