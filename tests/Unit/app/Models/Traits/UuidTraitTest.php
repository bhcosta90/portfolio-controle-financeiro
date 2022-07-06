<?php

namespace Tests\Unit\app\Models\Traits;

use App\Models\Traits\UuidTrait;
use PHPUnit\Framework\TestCase;

class UuidTraitTest extends TestCase
{
    public function testTraitUuid()
    {
        foreach (glob(__DIR__ . "/../../../../../app/Models/*.php") as $filename) {
            $fileModel = str_replace(__DIR__ . "/../../../../../app/", '', $filename);
            $fileClass = "App\\" . str_replace('/', "\\", substr($fileModel, 0, -4));
            $objClass = new $fileClass;
            $this->assertArrayHasKey(UuidTrait::class, class_uses($objClass), 'Model ' . $fileModel . ' do not implemented UuidTrait');
        }
    }
}
