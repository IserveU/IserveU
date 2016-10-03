<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('files', function(Blueprint $table) {
            $table->increments('id');
            $table->string('filename');
            $table->string('folder')->nullable();
            $table->string('slug');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('replacement_id')->unsigned()->nullable();

            $table->string('type')->default('image');
            $table->string('mime');

            $table->morphs('fileable');

            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });


        Schema::table('files', function(Blueprint $table) {
            $table->foreign('replacement_id')->references('user_id')->on('files');
        });


        Schema::table('users', function($table){
            $table->integer('government_identification_id')->nullable()->unsigned();
            $table->integer('avatar_id')->nullable()->unsigned();

            $table->foreign('government_identification_id')->references('id')->on('files');
            $table->foreign('avatar_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


        Schema::table('users', function($table){
            $table->dropForeign('users_avatar_id_foreign');
            $table->dropForeign('users_government_identification_id_foreign');
            $table->dropColumn('government_identification_id');
            $table->dropColumn('avatar_id');
        });

        Schema::drop('files');
    }
}
