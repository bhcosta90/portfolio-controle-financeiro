<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAccountsTable.
 */
class CreateAccountsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('accounts', function(Blueprint $table) {
            $table->id('id');
            $table->foreignId('user_id')->index()->constrained('users');
            $table->unsignedDouble('value');
            $table->string('bank_code', 4);
            $table->string('bank_account', 20);
            $table->string('bank_digit', 2);
            $table->timestamps();

            $table->unique(['bank_code', 'bank_account', 'bank_digit']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('accounts');
	}
}
