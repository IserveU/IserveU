<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('position')->nullable();
            $table->integer('motion_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('deferred_to_id')->unsigned(0)->nullable();
            $table->boolean('visited')->default(false);

            $table->softDeletes();
            $table->timestamps();
        });



        Schema::table('votes', function ($table) {
            $table->foreign('motion_id')->references('id')->on('motions');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unique(['motion_id', 'user_id']); //A user can only vote once on a motion
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('votes');
    }
}
