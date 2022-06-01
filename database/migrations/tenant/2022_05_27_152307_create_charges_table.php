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
            $table->foreignUuid('recurrence_id')->nullable()->constrained('recurrences');
            $table->foreignUuid('relationship_id')->nullable()->constrained('relationships');
            $table->string('relationship_type')->nullable();
            $table->string('entity')->index();
            $table->uuid('uuid')->index();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->date('date_start')->nullable();
            $table->date('date_finish')->nullable();
            $table->date('date_due')->nullable();
            $table->unsignedTinyInteger('parcel_total')->nullable();
            $table->unsignedTinyInteger('parcel_actual')->nullable();
            $table->unsignedTinyInteger('status');
            $table->unsignedFloat('value_charge');
            $table->unsignedFloat('value_pay');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['id', 'entity']);
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
