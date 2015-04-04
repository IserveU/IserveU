<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyAssesmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('property_assesments', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('land_value')->unsigned();
            $table->integer('improvement_value')->unsigned();
            $table->integer('other_value')->unsigned();
            $table->integer('year')->unsigned();
            $table->integer('property')->unsigned();
            $table->timestamps();
        });

        Schema::table('property_assesments', function($table){
 			$table->foreign('property')->references('id')->on('properties');
        });



	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{


		Schema::drop('property_assesments');
	}

}
