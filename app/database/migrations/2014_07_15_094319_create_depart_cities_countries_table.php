<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDepartCitiesCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('depart_cities_countries', function(Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->integer('depcity_id')->unsigned()->index();
			$table->integer('country_id')->unsigned()->index();
			$table->primary(array('depcity_id', 'country_id'));
			$table->foreign('depcity_id')->references('id')->on('depart_cities')->onDelete('cascade');
			$table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('depart_cities_countries');
	}

}
