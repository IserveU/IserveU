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
            $table->string('summary');
            $table->boolean('active')->default(0);
        	$table->integer('department_id')->unsigned()->default(1);
            $table->dateTime('closing')->nullable()->default(null);
            $table->integer('user_id')->unsigned();
            $table->text('text');
            $table->softDeletes();            
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
