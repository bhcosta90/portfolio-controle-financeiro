<?php

namespace Tests\Stub\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserStub extends Authenticatable{
    public static function createTable()
    {
        Schema::create('user_stubs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public static function dropTable()
    {
        Schema::dropIfExists('user_stubs');
    }
}
