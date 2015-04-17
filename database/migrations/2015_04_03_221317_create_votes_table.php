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
            $table->integer('motion_id')->unsigned();
            $table->integer('user_id')->unsigned();
        //v2    $table->integer('delegation')->unsigned();
   			$table->softDeletes();
            $table->timestamps();
        });



        Schema::table('votes', function($table){
        	$table->foreign('motion_id')->references('id')->on('motions');
 			$table->foreign('user_id')->references('id')->on('users');
 			$table->unique(array('motion_id','user_id'));
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
