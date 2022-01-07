<?php

namespace App\Traits;

use Carbon\Carbon;
use Exception;

trait ChargeTrait
{
    public function calculate(string $type, Carbon $date, Carbon $dateActual = null, $params = [])
    {
        switch($type){
            case 'month':
                $ret = $this->calculateMonth($date, $dateActual, $params);
                break;
            case 'week':
                $ret = $this->calculateWeek($date, $dateActual, $params);
                break;
            case 'twoweek':
                $ret = $this->calculateTwoWeek($date, $dateActual, $params);
                break;
            case 'fifth_business_day':
                $ret = $this->calculateFifthBusinessDay($date, $dateActual, $params);
                break;
            case 'every_20th':
                $ret = $this->calculateEvery20th($date, $dateActual, $params);
                break;
            case 'every_last_day':
                $ret = $this->calculateEveryLastDay($date, $dateActual, $params);
                break;
            default:
                throw new Exception("type `${type}` do not implemented");
        }

        $weekDay = array_map(fn ($date) => $this->onlyDayWeek(new Carbon($date), 0, false)->format('Y-m-d'), $ret);
        return [
            'date_original' => $ret,
            'date_week' => $weekDay,
        ];
    }
    protected function calculateMonth(Carbon $date, Carbon $dateActual = null, $params = []): array
    {
        $myDate = $date->format('Y-m-d');
        $dateActual = $this->getDateActual($dateActual)
            ->lastOfMonth()
            ->format('Y-m-d');

        $firstRegister = $params['first_date'] ?? true;

        $i = 0;

        $ret = [];
        do {
            $myDateFormat = (new Carbon($myDate))->addMonth($i)->format('Y-m-d');
            if ($firstRegister) {
                $ret[] = [
                    'date' => $myDateFormat,
                    'verify' => $myDateFormat < $dateActual,
                ];
            }

            $firstRegister = true;
            $i++;
        } while ($myDateFormat < $dateActual);

        return $this->validateReturn($ret);
    }

    protected function calculateWeek(Carbon $date, Carbon $dateActual = null, $params = []): array
    {
        $myDate = $date->format('Y-m-d');
        $myDate = $date->format('Y-m-d');
        $dateActual = $this->getDateActual($dateActual)
            ->lastOfMonth()
            ->format('Y-m-d');

        $firstRegister = $params['first_date'] ?? true;

        $i = 0;

        $ret = [];
        do {
            $myDateFormat = (new Carbon($myDate))->addWeek($i)->format('Y-m-d');
            if ($firstRegister) {
                $ret[] = [
                    'date' => $myDateFormat,
                    'verify' => $myDateFormat < $dateActual,
                ];
            }

            $firstRegister = true;
            $i++;
        } while ($myDateFormat < $dateActual);

        return $this->validateReturn($ret);
    }

    protected function calculateTwoWeek(Carbon $date, Carbon $dateActual = null, $params = []): array
    {
        $myDate = $date->format('Y-m-d');
        $myDate = $date->format('Y-m-d');
        $dateActual = $this->getDateActual($dateActual)
            ->lastOfMonth()
            ->format('Y-m-d');

        $firstRegister = $params['first_date'] ?? true;

        $i = 0;

        $ret = [];
        do {
            $myDateFormat = (new Carbon($myDate))->addWeek($i * 2)->format('Y-m-d');
            if ($firstRegister) {
                $ret[] = [
                    'date' => $myDateFormat,
                    'verify' => $myDateFormat < $dateActual,
                ];
            }

            $firstRegister = true;
            $i++;
        } while ($myDateFormat < $dateActual);

        return $this->validateReturn($ret);
    }

    protected function calculateFifthBusinessDay(Carbon $date, Carbon $dateActual = null, $params = []): array
    {
        $myDate = $date->format('Y-m-d');
        $dateActual = $this->getDateActual($dateActual)
            ->lastOfMonth()
            ->format('Y-m-d');

        $firstRegister = $params['first_date'] ?? true;

        $i = 0;

        $ret = [];
        do {
            $myDateCarbon = $this->onlyDayWeek((new Carbon($myDate))->addMonth($i)->firstOfMonth());
            $myDateFormat = $myDateCarbon->format('Y-m-d');

            if ($firstRegister) {
                $ret[] = [
                    'date' => $myDateFormat,
                    'verify' => $myDateFormat < $dateActual,
                ];
            }

            $firstRegister = true;
            $i++;
        } while ($myDateFormat < $dateActual);

        return $this->validateReturn($ret);
    }

    protected function calculateEvery20th(Carbon $date, Carbon $dateActual = null, $params = []): array
    {
        $myDate = $date->format('Y-m-d');
        $dateActual = $this->getDateActual($dateActual)
            ->lastOfMonth()
            ->format('Y-m-d');

        $firstRegister = $params['first_date'] ?? true;

        $i = 0;

        $ret = [];
        do {
            $myDateCarbon = (new Carbon($myDate))->addMonth($i)->setDay(20);
            $myDateFormat = $myDateCarbon->format('Y-m-d');

            if ($firstRegister) {
                $ret[] = [
                    'date' => $myDateFormat,
                    'verify' => $myDateFormat < $dateActual,
                ];
            }

            $firstRegister = true;
            $i++;
        } while ($myDateFormat < $dateActual);

        return $this->validateReturn($ret);
    }

    protected function calculateEveryLastDay(Carbon $date, Carbon $dateActual = null, $params = []): array
    {
        $myDate = $date->format('Y-m-d');
        $dateActual = $this->getDateActual($dateActual)
            ->lastOfMonth()
            ->addDay()
            ->format('Y-m-d');

        $firstRegister = $params['first_date'] ?? true;

        $i = 0;

        $ret = [];
        do {
            $myDateFormat = (new Carbon($myDate))->firstOfMonth()->addMonth($i)->lastOfMonth()->format('Y-m-d');

            if ($firstRegister) {
                $ret[] = [
                    'date' => $myDateFormat,
                    'verify' => $myDateFormat < $dateActual,
                ];
            }

            $firstRegister = true;
            $i++;
        } while ($myDateFormat <= $dateActual);

        return $this->validateReturn($ret);
    }

    protected function onlyDayWeek(Carbon $date, $quantity = 5, $nextDay = true): Carbon
    {
        $i = 0;
        $dataInit = clone $date;

        while (($i + 1) < $quantity || in_array($dataInit->format('N'), [6, 7])) {
            if (!in_array($dataInit->format('N'), [6, 7])) {
                $i++;
            }

            if ($nextDay == true) {
                $dataInit->addDay(1);
            } else {
                $dataInit->subDay(1);
            }
        }

        return $dataInit;

    }

    protected function getDateActual(Carbon $date)
    {
        if (is_null($date)) {
            return new Carbon();
        }

        return $date;
    }

    protected function validateReturn(array $dates)
    {
        $datesMap = array_map(fn ($ret) => $ret['date'], array_filter($dates, fn ($date) => $date['verify']));
        $datesUnique = array_unique($datesMap);
        return $datesUnique;
    }
}
