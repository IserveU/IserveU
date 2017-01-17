<?php

use App\Community;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddCommunityAdjective extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('communities', function ($table) {
            $table->string('adjective')->nullable()->default('Person');
            $table->string('slug');
        });

        foreach (Community::all() as $community) {
            $community->adjective = 'Person From '.$community->name;
            $community->save();
        }

        Schema::table('communities', function ($table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('communities', function ($table) {
            $table->dropColumn('adjective');
            $table->dropColumn('slug');
        });
    }
}
