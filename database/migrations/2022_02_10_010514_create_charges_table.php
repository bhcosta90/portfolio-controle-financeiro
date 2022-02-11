<?php

use App\Models\Charge;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateChargesTable.
 */
class CreateChargesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('charges', function(Blueprint $table) {
            $table->id('id');
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('recurrency_id')->nullable()->constrained('recurrencies');
            $table->morphs('chargeable');
            $table->morphs('basecharge');
            $table->string('resume');
            $table->text('description')->nullable();
            $table->unsignedDouble('value');
            $table->unsignedDouble('value_recurrency');
            $table->unsignedDouble('value_pay')->nullable();
            $table->string('customer_name');
            $table->date('due_date');
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->unsignedInteger('parcel_actual')->nullable();
            $table->unsignedInteger('parcel_total')->nullable();
            $table->enum('status', array_keys(Charge::getStatusOptionsAttribute()))->default('PE');
            $table->timestamps();
            $table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('charges');
	}
}
