<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FilesMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('figures'); //Easier to just drop this

        Schema::create('file_categories', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('files', function(Blueprint $table) {
            $table->increments('id');
            $table->string('filename');
            $table->string('title')->nullable();
            $table->boolean('image')->default(0);
            $table->integer('file_category_id')->unsigned();
            $table->timestamps();

            $table->foreign('file_category_id')->references('id')->on('file_categories');
        });

        Schema::create('motion_files', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('file_id')->unique()->unsigned();
            $table->integer('motion_id')->unsigned();
            $table->timestamps();

            $table->foreign('motion_id')->references('id')->on('motions');
            $table->foreign('file_id')->references('id')->on('files');
        });


        Schema::table('users', function($table){
            $table->integer('government_identification_id')->nullable()->unsigned();
            $table->integer('avatar_id')->nullable()->unsigned();

            $table->foreign('government_identification_id')->references('id')->on('files');
            $table->foreign('avatar_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('figures'); //Easier to just drop this

    }
}
