<?php

use App\Models\FormPayment;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateFormPaymentsTable.
 */
class CreateFormPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_payments', function (Blueprint $table) {
            $table->id('id');
            $table->enum('type', FormPayment::TYPES_FORM_PAYMENT);
            $table->json('sync_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('form_payments');
    }
}
