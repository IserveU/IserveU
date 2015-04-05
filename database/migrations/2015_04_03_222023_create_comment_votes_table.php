<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentVotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comment_votes', function(Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('position');
            $table->integer('comment')->unsigned();
            $table->integer('vote')->unsigned();
            $table->timestamps();
        });

        Schema::table('comment_votes', function($table){
 			$table->foreign('comment')->references('id')->on('comments');
 			$table->foreign('vote')->references('id')->on('votes');
 			$table->unique(array('comment','vote')); //On a particular comment you can only vote once
        });
        
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comment_votes');
	}

}
