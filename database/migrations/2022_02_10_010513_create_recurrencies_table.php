<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRecurrenciesTable.
 */
class CreateRecurrenciesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recurrencies', function(Blueprint $table) {
            $table->id('id');
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->string('type');
            $table->string('name');
            $table->boolean('can_updated')->nullable()->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'type']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('recurrencies');
	}
}
