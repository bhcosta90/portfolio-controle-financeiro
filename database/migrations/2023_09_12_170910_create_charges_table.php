<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->on('tenants');
            $table->foreignUuid('account_id')->nullable()->on('accounts');
            $table->uuidMorphs('charge');
            $table->foreignUuid('category_id')->nullable();
            $table->foreignUuid('sub_category_id')->nullable();
            $table->unsignedTinyInteger('type');
            $table->date('due_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charges');
    }
};
