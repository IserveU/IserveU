<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class JsonFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $motionsStore = DB::table('motions')->get()->pluck('text', 'id');

        Schema::table('motions', function ($table) {
            $table->dropColumn('text');
        });

        Schema::table('motions', function ($table) {
            $table->json('content')->nullable();
        });

        $motions = \App\Motion::all();

        foreach ($motions as $motion) {
            $motion->text = $motionsStore[$motion->id];
            $motion->save();
        }

        $pagesStore = DB::table('pages')->get()->pluck('content', 'id'); // \App\Page::all()->pluck('content', 'id');

        Schema::table('pages', function ($table) {
            $table->dropColumn('content');
        });

        Schema::table('pages', function ($table) {
            $table->json('content')->nullable();
        });

        $pages = \App\Page::all();

        foreach ($pages as $page) {
            $page->text = $pagesStore[$page->id];
            $page->save();
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
