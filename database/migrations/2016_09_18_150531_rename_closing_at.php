<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameClosingAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        if(Schema::hasColumn('motions','closing')){
            Schema::table('motions', function(Blueprint $table) {
               
                $table->renameColumn('closing','closing_at');     
                
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('motions', function(Blueprint $table){

            $table->renameColumn('closing_at','closing'); 
        });
    }
}
