<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected static \Illuminate\Foundation\Auth\User $user;

    protected function setUp(): void
    {
        parent::setUp();
        self::$user = User::factory()->create();
        $this->be(self::$user);
    }
}
