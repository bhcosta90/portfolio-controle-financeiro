<?php

namespace Tests\Unit\Traits;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Tests\Stub\Traits\ChargeTraitStub;

class ChargeUpTraitTest extends TestCase
{
    private ChargeTraitStub $obj;

    private Carbon $dateStart;

    private Carbon $dateFinish;

    private Carbon $dateFinish2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new ChargeTraitStub;

        $this->dateStart = (new Carbon())->setDay(20)->setMonth(8)->setYear(2020);
        $this->dateFinish = (new Carbon())->setDay(25)->setMonth(9)->setYear(2020);
        $this->dateFinish2 = (new Carbon())->setDay(25)->setMonth(10)->setYear(2020);
    }

    public function testIfUseTraits()
    {
        $array = [
            \App\Traits\ChargeTrait::class,
        ];

        $modelTraits = array_keys(class_uses(ChargeTraitStub::class));
        $this->assertEqualsCanonicalizing($array, $modelTraits);
    }

    public function testMonthDayBefore()
    {
        $dates = $this->obj->calculate('month', $this->dateStart, $this->dateFinish);
        $this->assertEquals([
            'date_original' => [
                '2020-08-20',
                '2020-09-20',
            ],
            'date_week' => [
                '2020-08-20',
                '2020-09-18',
            ]
        ], $dates);

        $dates = $this->obj->calculate('month', new Carbon("2020-09-20"), (new Carbon("2020-09-20"))->addMonth(), [
            'first_date' => false
        ]);
        $this->assertEquals([
            'date_original' => ['2020-10-20'],
            'date_week' => ['2020-10-20'],
        ], $dates);
    }

    public function testMonthDayBeforeTwo()
    {
        $dates = $this->obj->calculate('month', $this->dateStart, $this->dateFinish2);
        $this->assertEquals([
            'date_original' => [
                "2020-08-20",
                "2020-09-20",
                "2020-10-20"
            ],
            'date_week' => [
                "2020-08-20",
                "2020-09-18",
                "2020-10-20"
            ],
        ], $dates);
    }

    public function testWeekDayBefore()
    {
        $dates = $this->obj->calculate('week', $this->dateStart, $this->dateFinish);
        $this->assertEquals([
            'date_original' => [
                "2020-08-20",
                "2020-08-27",
                "2020-09-03",
                "2020-09-10",
                "2020-09-17",
                "2020-09-24",
            ],
            'date_week' => [
                "2020-08-20",
                "2020-08-27",
                "2020-09-03",
                "2020-09-10",
                "2020-09-17",
                "2020-09-24",
            ],
        ], $dates);

        $dates = $this->obj->calculate('week', new Carbon("2020-09-24"), (new Carbon("2020-09-24"))->addMonth(), [
            'first_date' => false
        ]);

        $this->assertEquals([
            'date_original' => [
                "2020-10-01",
                "2020-10-08",
                "2020-10-15",
                "2020-10-22",
                "2020-10-29",
            ],
            'date_week' => [
                "2020-10-01",
                "2020-10-08",
                "2020-10-15",
                "2020-10-22",
                "2020-10-29",
            ]
        ], $dates);
    }

    public function testWeekDayBeforeTwo()
    {
        $dates = $this->obj->calculate('week', $this->dateStart, $this->dateFinish2);
        $this->assertEqualsCanonicalizing([
            'date_original' => [
                "2020-08-20",
                "2020-08-27",
                "2020-09-03",
                "2020-09-10",
                "2020-09-17",
                "2020-09-24",
                "2020-10-01",
                "2020-10-08",
                "2020-10-15",
                "2020-10-22",
                "2020-10-29",
            ],
            'date_week' => [
                "2020-08-20",
                "2020-08-27",
                "2020-09-03",
                "2020-09-10",
                "2020-09-17",
                "2020-09-24",
                "2020-10-01",
                "2020-10-08",
                "2020-10-15",
                "2020-10-22",
                "2020-10-29",
            ]
        ], $dates);
    }

    public function testTwoWeekDayBefore()
    {
        $dates = $this->obj->calculate('twoweek', $this->dateStart, $this->dateFinish);
        $this->assertEquals([
            'date_original' => [
                "2020-08-20",
                "2020-09-03",
                "2020-09-17",
            ],
            'date_week' => [
                "2020-08-20",
                "2020-09-03",
                "2020-09-17",
            ],
        ], $dates);

        $dates = $this->obj->calculate('twoweek', new Carbon("2020-09-17"), (new Carbon("2020-09-17"))->addMonth(), [
            'first_date' => false
        ]);

        $this->assertEquals([
            'date_original' => [
                "2020-10-01",
                "2020-10-15",
                "2020-10-29",
            ],
            'date_week' => [
                "2020-10-01",
                "2020-10-15",
                "2020-10-29",
            ],
        ], $dates);
    }

    public function testTwoWeekDayBeforeTwo()
    {
        $dates = $this->obj->calculate('twoweek', $this->dateStart, $this->dateFinish2);
        $this->assertEqualsCanonicalizing([
            'date_original' => [
                "2020-08-20",
                "2020-09-03",
                "2020-09-17",
                "2020-10-01",
                "2020-10-15",
                "2020-10-29",
            ],
            'date_week' => [
                "2020-08-20",
                "2020-09-03",
                "2020-09-17",
                "2020-10-01",
                "2020-10-15",
                "2020-10-29",
            ]
        ], $dates);
    }

    public function testFifthBusinessDay()
    {
        $dates = $this->obj->calculate('fifth_business_day', $this->dateStart, $this->dateFinish);

        $this->assertEqualsCanonicalizing([
            'date_original' => [
                "2020-08-07",
                "2020-09-07",
            ],
            'date_week' => [
                "2020-08-07",
                "2020-09-07",
            ]
        ], $dates);

        $dates = $this->obj->calculate('fifth_business_day', new Carbon("2020-09-07"), (new Carbon("2020-09-07"))->addMonth(), [
            'first_date' => false
        ]);

        $this->assertEqualsCanonicalizing([
            'date_original' => ["2020-10-07"],
            'date_week' => ["2020-10-07"],
        ], $dates);
    }

    public function testFifthBusinessDayTwo()
    {
        $dates = $this->obj->calculate('fifth_business_day', $this->dateStart, $this->dateFinish2);

        $this->assertEqualsCanonicalizing([
            'date_original' => [
                "2020-08-07",
                "2020-09-07",
                "2020-10-07",
            ],
            'date_week' => [
                "2020-08-07",
                "2020-09-07",
                "2020-10-07",
            ]
        ], $dates);
    }

    public function testEvery_20th()
    {
        $dates = $this->obj->calculate('every_20th', $this->dateStart, $this->dateFinish);

        $this->assertEqualsCanonicalizing([
            'date_original' => [
                "2020-08-20",
                "2020-09-20",
            ],
            'date_week' => [
                "2020-08-20",
                "2020-09-18",
            ]
        ], $dates);

        $dates = $this->obj->calculate('every_20th', new Carbon("2020-09-20"), (new Carbon("2020-09-20"))->addMonth(), [
            'first_date' => false
        ]);

        $this->assertEqualsCanonicalizing([
            'date_original' => ["2020-10-20"],
            'date_week' => ["2020-10-20"],
        ], $dates);
    }

    public function testEvery_20thTwo()
    {
        $dates = $this->obj->calculate('every_20th', $this->dateStart, $this->dateFinish2);

        $this->assertEqualsCanonicalizing([
            'date_original' => [
                "2020-08-20",
                "2020-09-20",
                "2020-10-20",
            ],
            'date_week' => [
                "2020-08-20",
                "2020-09-18",
                "2020-10-20",
            ]
        ], $dates);
    }

    public function testEveryLastDay()
    {
        $dates = $this->obj->calculate('every_last_day', $this->dateStart, $this->dateFinish);

        $this->assertEqualsCanonicalizing([
            'date_original' => [
                "2020-08-31",
                "2020-09-30",
            ],
            'date_week' => [
                "2020-08-31",
                "2020-09-30",
            ],
        ], $dates);

        $dates = $this->obj->calculate('every_last_day', new Carbon("2020-09-30"), (new Carbon("2020-09-20"))->addMonth(), [
            'first_date' => false
        ]);

        $this->assertEqualsCanonicalizing([
            'date_original' => ["2020-10-31"],
            'date_week' => ["2020-10-30"],
        ], $dates);
    }

    public function testEveryLastDayTwo()
    {
        $dates = $this->obj->calculate('every_last_day', $this->dateStart, $this->dateFinish2);

        $this->assertEqualsCanonicalizing([
            'date_original' => [
                "2020-08-31",
                "2020-09-30",
                "2020-10-31",
            ],
            'date_week' => [
                "2020-08-31",
                "2020-09-30",
                "2020-10-30",
            ]
        ], $dates);
    }
}
