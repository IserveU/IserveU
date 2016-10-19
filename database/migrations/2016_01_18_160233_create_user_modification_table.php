<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserModificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_modifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('modification_to_id')->unsigned();
            $table->integer('modification_by_id')->unsigned()->nullable();
            $table->text('fields');
            $table->softDeletes();

            $table->timestamps();
        });

        Schema::table('user_modifications', function ($table) {
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
        Schema::table('user_modifications', function ($table) {
            $table->dropForeign('user_modifications_modification_to_id_foreign');
            $table->dropForeign('user_modifications_modification_by_id_foreign');
        });

        Schema::drop('user_modifications');
    }
}
