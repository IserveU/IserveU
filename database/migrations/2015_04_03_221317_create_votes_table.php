<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('votes', function(Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('position');
            $table->integer('motion')->unsigned();
            $table->integer('user')->unsigned();
        //v2    $table->integer('delegation')->unsigned();
            $table->timestamps();
        });



        Schema::table('votes', function($table){
        	$table->foreign('motion')->references('id')->on('motions');
 			$table->foreign('user')->references('id')->on('users');
 			$table->unique(array('motion','user'));
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('votes');
	}

}
