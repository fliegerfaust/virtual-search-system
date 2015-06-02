<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHotelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hotels', function(Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->index();
			$table->integer('sletat_id')->unique()->unsigned()->index();
			$table->integer('resort_id')->unsigned()->index();
			$table->foreign('resort_id')->references('id')->on('resorts')->onDelete('cascade');
			$table->string('name', 60)->index();
			$table->string('star_name',20)->index();
			$table->integer('rate')->index();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hotels');
	}

}
