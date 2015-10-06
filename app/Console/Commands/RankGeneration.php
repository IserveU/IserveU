<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Motion;
use App\MotionRank;
use Carbon\Carbon;
use DB;
use Setting;
use Illuminate\Support\Collection;

class RankGeneration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'motions:rankgeneration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the ranks, close off the expired motions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $motions = Motion::active()->get();
        $now = Carbon::now();

        foreach ($motions as $motion){

            $motionRankAttr = DB::table('votes')->where('motion_id',$motion->id)->selectRaw('motion_id, sum(position) as rank')->groupBy('motion_id')->first();
            if(!$motionRankAttr){
               $rank = 0;
            } else {
                $rank = $motionRankAttr->rank;
            }

            $motionRank = MotionRank::create(array('rank'=>$rank,'motion_id'=>$motion->id));

            // If it is within the autoextend period
            if($motionRank->created_at['carbon']->diffInHours($motion->closing['carbon']) <= Setting::get('motion.hours_before_closing_autoextend',12)){ 

                $motionRanks = MotionRank::where('motion_id',$motion->id)->latest()->get();
                
                if($motionRanks->count()>=2){ // Make sure there are two records to compare against each other
                    $currentRank    =   $motionRanks[0]->rank;
                    $previousRank   =   $motionRanks[1]->rank;
                    if(($currentRank>0 && $previousRank<0) || ($currentRank<0 && $previousRank>0)){ //If between these two it changed
                        $motion->closing = $motion->closing['carbon']->addHours(Setting::get('motion.hours_to_autoextend_by',12));
                        $motion->save();                
                    }
                }
            }

            if($motion->closing['carbon'] <= Carbon::now()){ // Close the motion, no more ranks after this one
                DB::table('motions')->where('id',$motion->id)->update(['active'=>0]);
            }       
        }
    }
}
