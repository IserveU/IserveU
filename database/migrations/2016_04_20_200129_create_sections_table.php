<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function($table) {
            $table->increments('id');
            $table->integer('order');

            $table->json('content');
            $table->string('type',64);
            $table->morphs('sectionable');

            $table->timestamps();
        });

        Schema::create('text_section', function (Blueprint $table) {
            $table->increments('id');
            $table->text('text');

            $table->timestamps();
        });

         Schema::create('inline_photo_section', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caption')->nullable();
            $table->string('url');

            $table->timestamps();
        });

        Schema::create('inline_photo_section_files', function (Blueprint $table) {
            $table->string('url');

            $table->increments('id');
            $table->integer('files_id')->unsigned();
            $table->integer('inline_photo_section_id')->unsigned();
            $table->string('location')->nullable;

            $table->foreign('files_id')->references('id')->on('files')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('inline_photo_section_id')->references('id')->on('inline_photo_section')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sections');
        Schema::drop('text_section');
        Schema::drop('inline_photo_section');
        Schema::drop('inline_photo_section_files');
    }
}
