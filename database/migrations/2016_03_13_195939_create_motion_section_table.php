<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMotionSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motion_section', function(Blueprint $table) {
            $table->increments('id');
            $table->text('content')->nullable();
            $table->integer('motion_id')->unsigned();
            $table->foreign('motion_id')->references('id')
                  ->on('motions')
                  ->onUpdate('cascade')->onDelete('cascade');

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
        Schema::drop('motion_section');
    }
}
