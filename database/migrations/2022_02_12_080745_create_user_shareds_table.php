<?php

use App\Models\UserShared;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSharedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_shareds', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_origin_id')->constrained('users');
            $table->foreignId('user_shared_id')->nullable()->constrained('users');
            $table->string('email');
            $table->enum('status', array_keys(UserShared::getStatusAttribute()));
            $table->timestamps();

            $table->unique(['user_origin_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_shareds');
    }
}
