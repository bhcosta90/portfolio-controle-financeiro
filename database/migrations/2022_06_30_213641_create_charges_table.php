<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('group_id')->index();
            $table->foreignUuid('tenant_id')->constrained('tenants');
            $table->foreignUuid('recurrence_id')->nullable()->constrained('recurrences');
            $table->foreignUuid('relationship_id')->constrained('relationships');
            $table->string('relationship_type')->index();
            $table->string('entity')->index();
            $table->string('title');
            $table->string('resume')->nullable();
            $table->unsignedDouble('value_charge');
            $table->unsignedDouble('value_pay')->default(0);
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('status');
            $table->unsignedInteger('parcel_actual');
            $table->unsignedInteger('parcel_total');
            $table->date('date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charges');
    }
};
