<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveProperties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table){

            $table->dropForeign('users_property_id_foreign');


            $table->dropColumn('property_id');

            $table->string('postal_code')->nullable();
            $table->string('street_name')->nullable();
            $table->string('street_number')->nullable();
            $table->string('unit_number')->nullable();

        });


        Schema::drop('property_assesments');
        Schema::drop('properties');
        Schema::drop('property_descriptions');
        Schema::drop('property_zonings');
        Schema::drop('property_coordinates');
        Schema::drop('property_blocks');
        Schema::drop('property_plans');
        Schema::drop('property_poll_divisions');


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
