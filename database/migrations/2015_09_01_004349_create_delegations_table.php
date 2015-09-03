<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\User;
use App\Department;
use App\Delegation;

class CreateDelegationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delegations', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('delegate_to_id');
            $table->integer('delegate_from_id');
            $table->integer('department_id');
            $table->timestamps();
        });


        $validUsers = User::validVoter()->get();
        $councillors = User::councillor()->get();
        $departments = Department::all();

        foreach($validUsers as $user){
            foreach($departments as $department){
                $newDelegation = new Delegation;

            }
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('delegations');
    }
}