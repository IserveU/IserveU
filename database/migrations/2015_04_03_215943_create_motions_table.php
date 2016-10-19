<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('summary');
            $table->string('slug')->unique();
            $table->text('text');

            $table->integer('department_id')->unsigned()->default(1);
            $table->dateTime('closing_at')->nullable()->default(null);
            $table->integer('user_id')->unsigned();
            $table->string('status')->default('draft');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('motions', function ($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motions', function ($table) {
            $table->dropForeign('motions_user_id_foreign');
        });

        Schema::drop('motions');
    }
}
