<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBackgroundImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('background_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_id')->unsigned();

            $table->foreign('file_id')->references('id')->on('files');
            
            $table->integer('user_id')->unsigned();
            $table->string('credited');
            $table->string('url');
            $table->boolean('active')->default(0);
            $table->date('display_date')->nullable();
            $table->timestamps();
        });

        Schema::table('background_images', function($table){
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
        Schema::table('background_images', function($table){
            $table->dropForeign('background_images_file_id_foreign');
        });

        Schema::drop('background_images');        
    }
}
