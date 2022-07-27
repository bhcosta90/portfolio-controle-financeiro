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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants');
            $table->uuid('group_id')->index();
            $table->foreignUuid('account_to_id')->constrained('accounts');
            $table->foreignUuid('account_from_id')->constrained('accounts');
            $table->uuid('relationship_id')->nullable();
            $table->string('relationship_type')->nullable();
            $table->string('relationship_name')->nullable();
            $table->string('title');
            $table->uuidMorphs('entity');
            $table->unsignedDouble('value');
            $table->double('previous_value');
            $table->unsignedTinyInteger('status');
            $table->unsignedTinyInteger('type');
            $table->dateTime('date');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['relationship_id', 'relationship_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
