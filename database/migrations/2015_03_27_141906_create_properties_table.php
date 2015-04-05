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
            
            $table->integer('property_block_id')->unsigned();
            $table->integer('property_coordinate_id')->unsigned();
            $table->integer('property_plan_id')->unsigned();
            $table->integer('property_poll_division_id')->unsigned();
            $table->integer('property_zoning_id')->unsigned();
            $table->integer('property_description_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('properties', function($table){
 			$table->foreign('property_block_id')->references('id')->on('property_blocks');
            $table->foreign('property_coordinate_id')->references('id')->on('property_coordinates');
            $table->foreign('property_plan_id')->references('id')->on('property_plans');
            $table->foreign('property_poll_division_id')->references('id')->on('property_poll_divisions');
            $table->foreign('property_zoning_id')->references('id')->on('property_zonings');
            $table->foreign('property_description_id')->references('id')->on('property_descriptions');
        });

        Schema::table('users',function($table){
            $table->foreign('property_id')->references('id')->on('properties');
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
            $table->dropForeign('users_property_id_foreign');
        });

		Schema::drop('properties');

	}

}
