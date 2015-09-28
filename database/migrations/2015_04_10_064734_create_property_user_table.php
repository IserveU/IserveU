<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('property_user', function(Blueprint $table){ //Stores the address(s) that the user lives at
			$table->increments('id');
			$table->date('verified_until')->nullable(); //Only an admin can update this field, it is null until an admin has checked it against the elections canada Database
			$table->integer('property_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->timestamps();

			$table->foreign('property_id')->references('id')->on('properties');
			$table->foreign('user_id')->references('id')->on('users');

		});

	
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('property_user');
	}

}
