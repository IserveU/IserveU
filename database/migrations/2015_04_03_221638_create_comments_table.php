<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('motion')->unsigned();
            $table->text('text');
            $table->integer('user')->unsigned();
            $table->timestamps();
        });

        Schema::table('comments', function($table){
 			$table->foreign('motion')->references('id')->on('motions');
 			$table->foreign('user')->references('id')->on('users');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comments');
	}

}
