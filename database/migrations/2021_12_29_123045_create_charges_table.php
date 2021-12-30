<?php

use App\Models\Charge;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('user_id');
            $table->morphs('chargeable');
            $table->unsignedDouble('value');
            $table->string('customer_name');
            $table->date('due_date');
            $table->date('last_date')->nullable();
            $table->unsignedInteger('parcel_actual')->nullable();
            $table->unsignedInteger('parcel_total')->nullable();
            $table->enum('type', array_keys(Charge::$typeOptions))->nullable();
            $table->enum('status', array_keys(Charge::$statusOptions));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charges');
    }
}
