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
            $table->foreignUuid('charge_id')->constrained('charges');
            $table->foreignUuid('account_id')->constrained('accounts');
            $table->foreignUuid('bank_id')->nullable()->constrained('banks');
            $table->foreignUuid('relationship_id')->nullable()->constrained('relationships');
            $table->date('date_schedule')->nullable();
            $table->unsignedTinyInteger('type');
            $table->float('value_transaction');
            $table->float('value_payment');
            $table->boolean('completed')->index()->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['completed', 'date_schedule']);
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
