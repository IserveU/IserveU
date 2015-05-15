<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('text');
            $table->dateTime('time');
            $table->timestamps();
        });

        Schema::table('motions', function($table){
            $table->integer('event_id')->unsigned()->nullable();
            $table->foreign('event_id')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motions',function($table){
            $table->dropForeign('motions_event_id_foreign');
        });

        Schema::drop('events');
    }
}
