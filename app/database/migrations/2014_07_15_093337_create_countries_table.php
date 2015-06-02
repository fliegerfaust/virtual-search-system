<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('countries', function(Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->index();
			$table->integer('sletat_id')->unique()->unsigned()->index();
			$table->string('name', 60)->index();
			$table->integer('d_date_from')->index();
			$table->integer('d_date_to')->index();
			$table->integer('d_night_from')->index();
			$table->integer('d_night_to')->index();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('countries');
	}

}
