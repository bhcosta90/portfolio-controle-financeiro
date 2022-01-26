<?php

use App\Models\Account;
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
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->index()->constrained('users');
            $table->string('name', 100);
            $table->double('value');
            $table->string('bank_code', 4);
            $table->string('bank_agency', 20);
            $table->string('bank_account', 20);
            $table->string('bank_digit', 2);
            $table->boolean('can_deleted')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['bank_code', 'bank_agency', 'bank_account', 'bank_digit']);
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
