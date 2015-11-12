<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiguresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('figures', function(Blueprint $table) {
            $table->increments('id');
            $table->string('file');
            $table->integer('motion_id')->unsigned();
            $table->string('title')->nullable();
            $table->timestamps();
        });

        Schema::table('figures', function($table){
            $table->foreign('motion_id')->references('id')->on('motions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('figures');
    }
}
