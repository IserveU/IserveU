<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JsonFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $pagesStore = \App\Page::all()->pluck('content','id');

        Schema::table('pages', function ($table) {
            $table->dropColumn('content');
        });

        Schema::table('pages', function ($table) {
            $table->json('content')->nullable();
        });

        $pages = \App\Page::all();

        foreach($pages as $page){
            $page->text = $pagesStore[$page->id];
            $page->save();
        }

        $motionsStore = \App\Page::all()->pluck('text','id');

        Schema::table('motions', function ($table) {
            $table->dropColumn('text');
        });

        Schema::table('motions', function ($table) {
            $table->json('content')->nullable();
        });

        $motions = \App\motion::all();

        foreach($motions as $motion){
            $motion->text = $motionsStore[$motion->id];
            $motion->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
