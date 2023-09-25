<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('extracts', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignUuid('user_id')->on('users');
            $table->foreignUuid('account_id')->on('accounts');
            $table->uuidMorphs('model');
            $table->string('charge_type');
            $table->unsignedDouble('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extracts');
    }
};
