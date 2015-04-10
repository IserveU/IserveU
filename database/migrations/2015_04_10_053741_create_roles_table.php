<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('roles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name'); // motion creator, user editor
			$table->string('description'); // "Can create and edit motions", "Can edit other users and verify them"
			$table->timestamps();
		});

		Schema::create('role_user', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned()->index();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

			$table->integer('role_id')->unsigned()->index();
			$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

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
		Schema::drop('user_role');
		Schema::drop('roles');
	}

}
