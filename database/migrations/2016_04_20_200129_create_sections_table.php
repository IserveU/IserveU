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

     
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sections');
        Schema::dropIfExists('text_section');
        Schema::dropIfExists('motion_section');
        Schema::dropIfExists('inline_photo_section_files');
        Schema::dropIfExists('inline_photo_section');
    }
}
