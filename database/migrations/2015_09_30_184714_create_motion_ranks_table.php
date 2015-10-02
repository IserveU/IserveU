<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Vote;

use App\Events\VoteUpdated; //Move to migration


class CreateMotionRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Auth::loginUsingId(1);

        Schema::create('motion_ranks', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('rank');
            $table->integer('motion_id')->unsigned();
            $table->foreign('motion_id')->references('id')->on('motions');
            $table->timestamps();
        });


        $votes = Vote::all(); //Generate the motion ranks so far
        foreach($votes as $vote){
            event(new VoteUpdated($vote));
        }

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
