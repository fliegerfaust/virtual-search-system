<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVtConstantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vt_constants', function(Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->string('name', 30)->unique()->index();
			$table->integer('value')->index();
			$table->string('desc', 200);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vt_constants');
	}

}
