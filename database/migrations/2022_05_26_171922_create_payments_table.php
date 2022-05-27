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
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('charge_id')->constrained('charges');
            $table->foreignId('account_id')->constrained('accounts');
            $table->foreignId('bank_id')->nullable()->constrained('banks');
            $table->foreignId('relationship_id')->nullable()->constrained('relationships');
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
