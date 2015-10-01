<?php

namespace App\Listeners\Vote;

use App\Events\VoteCreated;
use App\Motion;
use App\MotionRank;
use DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Setting;
use Carbon\Carbon;

class CreateMotionRank
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
     * @param  VoteCreated  $event
     * @return void
     */
    public function handle($event)
    {
        $motion = $event->motion;
        $now = Carbon::now();


        if(!$motion->lastestRank || $motion->lastestRank->created_at['carbon']->diffInMinutes($now) >= Setting::get('motion.minutes_between_rank_calculations',60)){
            $motionRankAttr = DB::table('votes')->where('motion_id',$motion->id)->selectRaw('motion_id, sum(position) as rank')->groupBy('motion_id')->first();
            $motionRank = MotionRank::create(array('rank'=>$motionRankAttr->rank,'motion_id'=>$motion->id));
            
            if($motion->lastestRank->created_at['carbon']->diffInHours($motion->closing['carbon']) <= Setting::get('motion.hours_before_closing_autoextend',12)){ // If it is within the autoextend period

                $motionRanks = MotionRank::where('motion_id',$motion->id)->latest()->get();
                $currentRank    =   $motionRanks[0];
                $previousRank   =   $motionRanks[1];

                if(($currentRank>0 && $previousRank<0) || ($currentRank<0 && $previousRank>0)){ //If between these two it changed
                    $motion->closing = $motion->closing['carbon']->addHours(Setting::get('motion.hours_to_autoextend_by',12));
                    $motion->save();                
                }
            }
        }
    }
}