<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        /** @var \Illuminate\Foundation\Auth\User $user */
        $user = User::factory()->create();
        $this->be($user);
        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }
}
