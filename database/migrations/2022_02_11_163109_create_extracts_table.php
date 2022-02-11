<?php

use App\Models\Extract;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateExtractsTable.
 */
class CreateExtractsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('extracts', function(Blueprint $table) {
            $table->id('id');
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->morphs('extract');
            $table->morphs('base');
            $table->unsignedDouble('value_charge');
            $table->double('value_transfer');
            $table->string('name');
            $table->string('resume');
            $table->enum('type', array_keys(Extract::getTypeAttribute()));
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
		Schema::drop('extracts');
	}
}
