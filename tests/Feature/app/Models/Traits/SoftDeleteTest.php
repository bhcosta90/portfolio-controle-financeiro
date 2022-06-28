<?php

namespace Tests\Feature\app\Models\Traits;

use App\Models\{Account, Tenant, User};
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;

class SoftDeleteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $except = [
            // Tenant::class,
            // User::class,
            // Account::class,
        ];

        foreach (glob(app_path("Models/*.php")) as $filename) {
            $fileModel = str_replace(app_path() . '/', '', $filename);
            $fileClass = "App\\" . str_replace('/', "\\", substr($fileModel, 0, -4));

            if (in_array($fileClass, $except)) {
                continue;
            }

            $objClass = app($fileClass);
            $this->assertArrayHasKey(SoftDeletes::class, class_uses($objClass), 'Model ' . $fileModel . ' do not implemented SoftDeletes');
        }
    }
}
