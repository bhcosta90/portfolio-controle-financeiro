<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('account_banks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants');
            $table->string('name');
            $table->double('value')->default(0);
            $table->string('bank_code')->nullable();
            $table->string('bank_agency')->nullable();
            $table->string('bank_agency_digit')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_account_digit')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_banks');
    }
};
