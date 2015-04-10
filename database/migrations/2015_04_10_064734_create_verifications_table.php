<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('verifications', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('until');
			$table->integer('property_id')->unsigned();
			$table->foreign('property_id')->references('id')->on('properties');
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
		Schema::drop('verifications');
	}

}
