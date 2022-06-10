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
            $table->uuidMorphs('charge');
            $table->foreignUuid('account_from_id')->nullable()->constrained('accounts');
            $table->foreignUuid('account_to_id')->nullable()->constrained('accounts');
            $table->foreignUuid('relationship_id')->nullable()->constrained('relationships');
            $table->string('title')->nullable();
            $table->date('date_schedule')->nullable();
            $table->unsignedTinyInteger('type');
            $table->float('value_transaction');
            $table->float('value_payment');
            $table->boolean('completed')->index()->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['completed', 'date_schedule']);
            $table->index(['completed', 'date_schedule', 'account_from_id']);
            $table->index(['completed', 'date_schedule', 'account_to_id']);
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
