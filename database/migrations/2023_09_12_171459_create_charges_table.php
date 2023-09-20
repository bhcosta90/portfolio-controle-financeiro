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
            $table->uuid('group_id')->index();
            $table->foreignUuid('tenant_id')->on('tenants');
            $table->foreignUuid('account_id')->nullable()->on('accounts');
            $table->string('description')->nullable();
            $table->unsignedDouble('value');
            $table->uuidMorphs('charge');
            $table->foreignUuid('category_id')->nullable();
            $table->foreignUuid('sub_category_id')->nullable();
            $table->unsignedTinyInteger('type');
            $table->date('due_date');
            $table->string('day_charge');
            $table->text('note')->nullable();
            $table->boolean('is_deleted')->nullable()->default(null);
            $table->boolean('is_parcel')->nullable()->default(null);
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
