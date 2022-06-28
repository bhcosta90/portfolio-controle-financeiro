<?php

namespace Tests\Feature\app\Models\Traits;

use App\Models\{Account, Tenant, User};
use App\Models\Traits\UuidTrait;
use Tests\TestCase;

class UuidTraitTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        foreach (glob(app_path("Models/*.php")) as $filename) {
            $fileModel = str_replace(app_path() . '/', '', $filename);
            $fileClass = "App\\" . str_replace('/', "\\", substr($fileModel, 0, -4));
            $objClass = app($fileClass);
            $this->assertArrayHasKey(UuidTrait::class, class_uses($objClass), 'Model ' . $fileModel . ' do not implemented UuidTrait');
        }
    }
}
