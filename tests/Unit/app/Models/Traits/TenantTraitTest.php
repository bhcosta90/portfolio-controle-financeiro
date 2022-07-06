<?php

namespace Tests\Unit\app\Models\Traits;

use App\Models\{Tenant, User};
use App\Models\Traits\TenantTrait;
use PHPUnit\Framework\TestCase;

class TenantTraitTest extends TestCase
{
    public function testTraitTenant()
    {
        $except = [
            Tenant::class,
            User::class,
        ];

        foreach (glob(__DIR__ . "/../../../../../app/Models/*.php") as $filename) {
            $fileModel = str_replace(__DIR__ . "/../../../../../app/", '', $filename);
            $fileClass = "App\\" . str_replace('/', "\\", substr($fileModel, 0, -4));

            if (in_array($fileClass, $except)) {
                continue;
            }

            $objClass = new $fileClass;
            $this->assertArrayHasKey(TenantTrait::class, class_uses($objClass), 'Model ' . $fileModel . ' do not implemented TenantTrait');
        }
    }
}
