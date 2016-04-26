<?php

namespace App\Listeners\Motion;

use App\Events\Motion\MotionCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;
use App\Delegation;
use App\Department;
use DB;

class CreateDeferredVotes
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MotionCreated  $event
     * @return void
     */
    public function handle(MotionCreated $event)
    {
        $motion = $event->motion;
       //DB::enableQueryLog();
        $validVoters = User::with(['delegatedFrom'=>function($query) use ($motion){
            $query->where('department_id',$motion->department_id);
        }])->validVoter()->notRepresentative()->get();

    //  echo print_r(DB::getQueryLog());

        $votes = array();

        foreach($validVoters as $validVoter){
            
            if(!$validVoter->delegatedFrom->isEmpty()){
                $votes[] = [
                    'motion_id'         =>       $motion->id,
                    'user_id'           =>       $validVoter->id,
                    'deferred_to_id'    =>       $validVoter->delegatedFrom->first()->delegate_to_id
                ];
            }
        }

        DB::table('votes')->insert($votes);

        $councillors = User::representative()->get();
    }
}
