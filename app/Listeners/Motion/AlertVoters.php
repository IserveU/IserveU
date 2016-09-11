<?php

namespace App\Listeners\Motion;

use App\Vote;
use App\Events\Motion\MotionUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class AlertVoters
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
     * Handle the event. Wonder if this could all just be a daily email if the "Updated" field has changed
     *
     * @param  MotionUpdated  $event
     * @return void
     */
    public function handle(MotionUpdated $event)
    {
        $motion = $event->motion;

        $changedFields = $motion->getAlteredLockedFields();

        if(!empty($changedFields)){



            $motionVotes = Vote::whereHas('user',function($query){
                $query->whereNull('deleted_at');
            })->where('motion_id',$motion->id)->get();

            foreach($motionVotes as $motionVote){
                $data = array(
                    'user'      =>  $motionVote->user,
                    'motion'    =>  $motion
                );


                Mail::send('emails.motionchanged',$data, function ($m) use ($motionVote) {
                    $m->to($motionVote->user->email, $motionVote->user->first_name.' '.$motionVote->user->last_name)->subject('A Motion You Voted On Has Changed');
                });
            }

        }
    }
}
