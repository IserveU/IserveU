<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->string('hash');
			$table->string('first_name');
			$table->string('middle_name')->nullable();
			$table->string('last_name');
			$table->integer('ethnic_origin')->unsigned()->nullable();
			$table->date('verified_until');
			$table->date('date_of_birth');
			$table->boolean('intrepid');
			$table->boolean('public');
			$table->boolean('administration');
			$table->integer('property')->unsigned();
			$table->rememberToken();
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
		Schema::drop('users');
	}

}
