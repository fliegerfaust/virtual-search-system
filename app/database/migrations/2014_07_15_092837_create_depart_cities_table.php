<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDepartCitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('depart_cities', function(Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->index();
			$table->integer('sletat_id')->unique()->unsigned()->index();
			$table->string('name', 60)->index();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('depart_cities');
	}

}
