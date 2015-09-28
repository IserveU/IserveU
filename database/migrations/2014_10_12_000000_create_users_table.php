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
		Schema::create('users', function(Blueprint $table){
			$table->increments('id');
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->string('first_name');
			$table->string('middle_name')->nullable();
			$table->string('last_name');
			$table->integer('ethnic_origin_id')->unsigned()->nullable();
			$table->date('date_of_birth')->nullable();
			$table->boolean('identity_verified')->default(0);
			$table->integer('login_attempts')->default(0);
			$table->datetime('locked_until')->nullable();
			$table->boolean('public')->default(0);
			$table->softDeletes();
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
