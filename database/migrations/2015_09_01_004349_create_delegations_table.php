<?php

use App\Delegation;
use App\Department;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDelegationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delegations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('delegate_to_id')->unsigned();
            $table->integer('delegate_from_id')->unsigned();
            $table->integer('department_id')->unsigned();
            $table->boolean('user_set')->default(0);

            $table->timestamps();
        });


        Schema::table('delegations', function ($table) {
            $table->unique(['department_id', 'delegate_from_id']); //A user can only vote once on a motion
            $table->foreign('delegate_to_id')->references('id')->on('users');
            $table->foreign('delegate_from_id')->references('id')->on('users');
            $table->foreign('department_id')->references('id')->on('departments');
        });


        $validUsers = User::notRepresentative()->get();
        $departments = Department::all();
        $numberOfRepresentatives = User::representative()->count();

        if (isset($numberOfRepresentative)) {
            foreach ($validUsers as $user) {
                foreach ($departments as $department) {
                    $representatives = User::representative()->get();
                    $leastDelegatedToRepresentative = $representatives->sortBy('totalDelegationsTo')->first();
                    $newDelegation = new Delegation();
                    $newDelegation->department_id = $department->id;
                    $newDelegation->delegate_from_id = $user->id;
                    $newDelegation->delegate_to_id = $leastDelegatedToRepresentative->id;
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
