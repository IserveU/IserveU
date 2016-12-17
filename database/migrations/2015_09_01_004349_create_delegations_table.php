<?php

use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDelegationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delegations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('delegate_to_id')->unsigned();
            $table->integer('delegate_from_id')->unsigned();
            $table->integer('department_id')->unsigned();
            $table->boolean('user_set')->default(0);

            $table->timestamps();
        });

        Schema::table('delegations', function ($table) {
            $table->unique(['department_id', 'delegate_from_id']); //A user can only vote once on a motion
            $table->foreign('delegate_to_id')->references('id')->on('users');
            $table->foreign('delegate_from_id')->references('id')->on('users');
            $table->foreign('department_id')->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('delegations');
    }
}
