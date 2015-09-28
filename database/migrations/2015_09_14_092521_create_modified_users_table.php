<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModifiedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modified_users', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('modification_to_id')->unsigned();
            $table->integer('modification_by_id')->unsigned()->nullable();
            $table->text('fields');
            $table->timestamps();
        });

        Schema::table('modified_users', function($table){
            $table->foreign('modification_to_id')->references('id')->on('users');
            $table->foreign('modification_by_id')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('modified_users');
    }
}
