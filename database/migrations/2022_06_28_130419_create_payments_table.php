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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants');
            $table->foreignUuid('entity_id')->nullable()->constrained('charges');
            $table->string('entity_type')->nullable()->constrained('charges');
            $table->foreignUuid('account_from_id')->nullable()->constrained('accounts');
            $table->foreignUuid('account_to_id')->nullable()->constrained('accounts');
            $table->unsignedFloat('value');
            $table->unsignedTinyInteger('status');
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
        Schema::dropIfExists('payments');
    }
};
