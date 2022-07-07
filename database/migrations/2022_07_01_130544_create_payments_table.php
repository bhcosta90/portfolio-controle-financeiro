<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants');
            $table->string('title');
            $table->string('resume')->nullable();
            $table->unsignedFloat('value');
            $table->unsignedFloat('value_bank')->nullable();
            $table->unsignedTinyInteger('status');
            $table->unsignedTinyInteger('type');
            $table->foreignUuid('relationship_id')->nullable()->constrained('relationships');
            $table->string('relationship_type')->nullable();
            $table->string('relationship_name')->nullable();
            $table->foreignUuid('charge_id')->nullable()->constrained('charges');
            $table->string('charge_type')->nullable();
            $table->foreignUuid('account_bank_id')->nullable()->constrained('account_banks');
            $table->dateTime('date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
