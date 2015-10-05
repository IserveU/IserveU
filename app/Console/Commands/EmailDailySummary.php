<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use App\Motion;
use Mail;
use Carbon\Carbon;

class EmailDailySummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the emails with daily summaries';

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
        $users = User::validVoter()->get();
        
        //Get users who want a daily summary
        $dailySummaryEmailUsers = $users->filter(function($user) {
            return $user->preferences['emails']['daily_summary'];
        });

        // Get latest or new motion
        $latestLaunchedMotions      =   Motion::active()->updatedAfter(Carbon::now()->subHours(24))->get();
        // \DB::enableQueryLog();
        $recentlyClosedMotions      =   Motion::expired()->closingAfter(Carbon::now()->subHours(24))->get();
        // echo print_r(\DB::getQueryLog());
        $closingSoonMotions         =   Motion::active()->closingAfter(Carbon::now())->closingBefore(Carbon::now()->addHours(24))->get();

        $data = array(
            "latestLaunchedMotions"     =>      $latestLaunchedMotions,
            "recentlyClosedMotions"     =>      $recentlyClosedMotions,
            "closingSoonMotions"        =>      $closingSoonMotions,
            "title"                     =>      "Daily Summary"
        );

        if($latestLaunchedMotions->isEmpty() && $recentlyClosedMotions->isEmpty() && $closingSoonMotions->isEmpty()){ //No updates today
            return true;
        }

        foreach($users as $user){
            Mail::send('emails.summary',$data, function ($m) use ($user) {
                $m->to($user->email, $user->first_name.' '.$user->last_name)->subject('Daily IserveU Summary');
            });
        }

    }
}
