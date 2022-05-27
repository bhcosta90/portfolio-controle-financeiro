<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->uuidMorphs('relationship');
            $table->morphs('charge');
            $table->foreignId('recurrence_id')->nullable()->constrained('recurrences');
            $table->uuid('uuid')->unique();
            $table->uuid('group_uuid')->index();
            $table->unsignedTinyInteger('parcel_total')->nullable();
            $table->unsignedTinyInteger('parcel_actual')->nullable();
            $table->string('title');
            $table->string('description')->nullable();
            $table->date('date_start');
            $table->date('date_finish');
            $table->date('date_due');
            $table->unsignedTinyInteger('status');
            $table->unsignedFloat('value_charge');
            $table->unsignedFloat('value_pay')->nullable();
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
};
