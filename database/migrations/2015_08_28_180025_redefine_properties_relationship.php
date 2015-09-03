<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RedefinePropertiesRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('property_user');

        Schema::table('users', function(Blueprint $table){ 
            $table->date('verified_until')->nullable();
            $table->integer('property_id')->nullable()->unsigned();
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
       
    }
}
