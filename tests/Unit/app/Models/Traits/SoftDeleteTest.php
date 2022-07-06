<?php

namespace Tests\Unit\app\Models\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class SoftDeleteTest extends TestCase
{
    public function testTraitSoftDeletes()
    {
        foreach (glob(__DIR__ . "/../../../../../app/Models/*.php") as $filename) {
            $fileModel = str_replace(__DIR__ . "/../../../../../app/", '', $filename);
            $fileClass = "App\\" . str_replace('/', "\\", substr($fileModel, 0, -4));
            $objClass = new $fileClass;
            $this->assertArrayHasKey(SoftDeletes::class, class_uses($objClass), 'Model ' . $fileModel . ' do not implemented SoftDeletes');
        }
    }
}
