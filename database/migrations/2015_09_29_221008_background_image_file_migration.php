<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\BackgroundImage;

class BackgroundImageFileMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        BackgroundImage::truncate();
        Schema::table('background_images', function($table){
            $table->dropColumn('file');
            $table->integer('file_id')->unsigned();
            $table->foreign('file_id')->references('id')->on('files');
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
    }
}
