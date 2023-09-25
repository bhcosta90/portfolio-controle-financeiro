<?php

namespace Tests;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Event;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $tenancy = true;

    public function setUp(): void
    {
        parent::setUp();
        
        // Event::fake([]);

        if ($this->tenancy) {
            $this->initializeTenancy();
        }
    }

    public function initializeTenancy()
    {
        $tenant = Tenant::create(['id' => str()->uuid()]);
        tenancy()->initialize($tenant);
    }
}
