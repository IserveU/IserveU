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
    //        $table->integer('motion_id')->unsigned(); Not good design
            $table->text('text');
            $table->softDeletes();
            $table->integer('vote_id')->unsigned()->unique();

            $table->timestamps();
        });

        Schema::table('comments', function($table){
        	$table->foreign('vote_id')->references('id')->on('votes'); //The user votes and can then make a comment
        });
        
        Schema::table('votes', function($table){
        });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::table('votes',function($table){
 //           $table->dropForeign('votes_comment_id_foreign');
        });

		Schema::drop('comments');
	}

}
