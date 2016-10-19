<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEthnicOriginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ethnic_origins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('region');
            $table->string('description');
            $table->timestamps();
        });

        Schema::table('users', function ($table) {
            $table->foreign('ethnic_origin_id')->references('id')->on('ethnic_origins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropForeign('users_ethnic_origin_id_foreign');
        });

        Schema::drop('ethnic_origins');
    }
}
