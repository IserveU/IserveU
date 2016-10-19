<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug');
            $table->boolean('active')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('motions', function ($table) {
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
        Schema::table('motions', function ($table) {
            $table->dropForeign('motions_department_id_foreign');
        });

        Schema::drop('departments');
    }
}
