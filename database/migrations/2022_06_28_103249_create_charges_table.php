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
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants');
            $table->foreignUuid('recurrence_id')->nullable()->constrained('recurrences');
            $table->foreignUuid('relationship_id')->constrained('relationships');
            $table->string('relationship_type');
            $table->string('entity')->index();
            $table->uuid('group_id')->index();
            $table->unsignedTinyInteger('status');
            $table->unsignedTinyInteger('type');
            $table->unsignedDouble('value_charge');
            $table->unsignedDouble('value_pay')->nullable();
            $table->date('date');
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
