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
            $table->text('text');
            $table->softDeletes();
            $table->boolean('status')->default('private'); 
            $table->integer('vote_id')->unsigned()->unique();

            $table->timestamps();
        });

        Schema::table('comments', function($table){
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
