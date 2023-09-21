<?php

namespace Tests\Unit\Filament\Resources\Charge\Traits;

use App\Filament\Resources\Charge\Traits\ParcelTrait;
use App\Models\Enum\Charge\ParcelEnum;
use Carbon\Carbon;
use Tests\TestCase;

class ParcelTraitTest extends TestCase
{
    protected $classAnonymous;
    protected function setUp(): void
    {
        parent::setUp();

        $this->classAnonymous = new class() {
            use ParcelTrait;
        };
    }

    public function test_example(): void
    {
        $resp = $this->classAnonymous->generateParcel(
            value: 1000,
            type: ParcelEnum::TOTAL,
            quantity: 3,
            date: new Carbon,
            description: 'test',
        );

        $this->assertEquals(333.33, $resp[0]['value']);
        $this->assertEquals(333.33, $resp[1]['value']);
        $this->assertEquals(333.34, $resp[2]['value']);

        $resp = $this->classAnonymous->generateParcel(
            value: 333.34,
            type: ParcelEnum::TOTAL,
            quantity: 5,
            date: new Carbon,
            description: 'test',
        );

        $this->assertEquals(66.66, $resp[0]['value']);
        $this->assertEquals(66.66, $resp[1]['value']);
        $this->assertEquals(66.66, $resp[2]['value']);
        $this->assertEquals(66.66, $resp[3]['value']);
        $this->assertEquals(66.71, $resp[4]['value']);
    }
}
