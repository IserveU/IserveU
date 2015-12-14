<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMotionStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motions', function($table){
            $table->tinyInteger('status')->default(0)->unsigned(); 

        });

        \DB::table('motions')->where('active',1)->update(['status'=>2]);

        Schema::table('motions', function($table){
            $table->dropColumn('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motions', function($table){
            $table->dropColumn('status');
            $table->boolean('active')->default(0);
        });
    }
}
