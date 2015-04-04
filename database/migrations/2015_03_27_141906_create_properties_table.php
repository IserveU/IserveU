<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('properties', function(Blueprint $table) {
            $table->increments('id');
            $table->string('unit');
            $table->string('roll_number')->unique();
            $table->string('address');
            $table->string('street');
            $table->string('postal_code')->nullable();
            
            $table->integer('property_block')->unsigned();
            $table->integer('property_coordinate')->unsigned();
            $table->integer('property_plan')->unsigned();
            $table->integer('property_poll_division')->unsigned();
            $table->integer('property_zoning')->unsigned();
            $table->integer('property_description')->unsigned();
            $table->timestamps();
        });

        Schema::table('properties', function($table){
 			$table->foreign('property_block')->references('id')->on('property_blocks');
            $table->foreign('property_coordinate')->references('id')->on('property_coordinates');
            $table->foreign('property_plan')->references('id')->on('property_plans');
            $table->foreign('property_poll_division')->references('id')->on('property_poll_divisions');
            $table->foreign('property_zoning')->references('id')->on('property_zonings');
            $table->foreign('property_description')->references('id')->on('property_descriptions');
        });

        Schema::table('users',function($table){
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
        Schema::table('users',function($table){
            $table->dropForeign('users_property_foreign');
        });

		Schema::drop('properties');

	}

}
