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
            $table->integer('motion_id')->unsigned();
            $table->text('text');
            $table->boolean('approved')->default(0); //Just for the conference
            $table->integer('vote_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('comments', function($table){
 			$table->foreign('motion_id')->references('id')->on('motions');
 			$table->foreign('vote_id')->references('id')->on('votes'); //The user votes and can then make a comment
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
