<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMotionRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motion_ranks', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('rank');
            $table->integer('motion_id')->unsigned();
            $table->foreign('motion_id')->references('id')->on('motions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('motion_ranks');
    }
}
