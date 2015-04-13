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
            $table->boolean('active')->default(0);
        //v2    $table->integer('department')->nullable();
            $table->date('closing_date');
            $table->integer('user_id')->unsigned();
            $table->text('text');
            $table->timestamps();
        });

        Schema::table('motions', function($table){
 			$table->foreign('user_id')->references('id')->on('users');
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
