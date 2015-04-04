<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMotionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('motions', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
        //v2    $table->integer('department')->nullable();
            $table->date('closing_date');
            $table->integer('user')->unsigned();
            $table->text('text');
            $table->timestamps();
        });

        Schema::table('motions', function($table){
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
		Schema::drop('motions');
	}

}
