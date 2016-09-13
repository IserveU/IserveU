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
            $table->integer('comment_id')->unsigned();
            $table->integer('vote_id')->unsigned();
            $table->timestamps();
   			$table->softDeletes();
        });

        Schema::table('comment_votes', function($table){
 			$table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
 			$table->foreign('vote_id')->references('id')->on('votes');
 			$table->unique(array('comment_id','vote_id')); //On a particular comment you can only vote once
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
