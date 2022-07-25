<?php

namespace Tests\Feature\app\Models;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TenantTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreate()
    {
        $objTenant = Tenant::factory()->create();
        $this->assertNotEmpty($objTenant->idAccount());
    }
}
