<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyCoordinatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('property_coordinates', function(Blueprint $table) {
            $table->increments('id');
            $table->double('latitude');
            $table->double('longitude');
            $table->integer('block')->unsigned();
            $table->integer('last_coordinate')->unsigned()->nullable();
            $table->timestamps();
        });

       	Schema::table('property_coordinates', function($table){
 			$table->foreign('block')->references('id')->on('property_blocks');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('property_coordinates');
	}

}
