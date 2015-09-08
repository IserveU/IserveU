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
            $table->integer('delegate_to_id')->unsigned();
            $table->integer('delegate_from_id')->unsigned();
            $table->integer('department_id')->unsigned();
            $table->boolean('user_set')->default(0);

            $table->timestamps();
        });


        Schema::table('delegations', function($table){
            $table->unique(array('department_id','delegate_from_id')); //A user can only vote once on a motion
            $table->foreign('delegate_to_id')->references('id')->on('users');            
            $table->foreign('delegate_from_id')->references('id')->on('users');
            $table->foreign('department_id')->references('id')->on('departments');
        });


        $validUsers = User::notCouncillor()->get();
        $departments = Department::all();
        $numberOfCouncilors = User::councillor()->count();

        if($numberOfCouncilors){
            foreach($validUsers as $user){
                foreach($departments as $department){
                    $councillors = User::councillor()->get();
                    $leastDelegatedToCouncillor = $councillors->sortBy('totalDelegationsTo')->first();
                    $newDelegation = new Delegation;
                    $newDelegation->department_id       =   $department->id;
                    $newDelegation->delegate_from_id    =   $user->id;
                    $newDelegation->delegate_to_id      =   $leastDelegatedToCouncillor->id;
                    $newDelegation->save();
                }
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