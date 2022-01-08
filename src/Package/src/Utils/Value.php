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

            $valueCalculate = $value / $total;
            $totalValue += $this->truncate($valueCalculate);
            $ret[] = ['value' => $valueCalculate, 'due_date' => $dataAtual->format('Y-m-d')];
        }

        $last = array_pop($ret);
        $totalRestante = $this->truncate($value - $totalValue);
        $ret[] = ['value' => $last['value'] + $totalRestante] + $last;

        return $ret;
    }

    public function truncate(float $valor, int $precisao = 2): float
    {
        return intval($valor * ($p = pow(10, $precisao))) / $p;
    }
}
