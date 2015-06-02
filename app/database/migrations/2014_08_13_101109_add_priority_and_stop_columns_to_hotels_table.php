<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriorityAndStopColumnsToHotelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hotels', function(Blueprint $table)
		{
			$table->integer('priority')->index();
			$table->boolean('stop');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('hotels', function(Blueprint $table)
		{
			$table->dropColumn('priority');
			$table->dropColumn('stop');
		});
	}

}
