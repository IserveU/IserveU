<?php

namespace App\Jobs\Emails;

use App\Motion;
use App\Notifications\Summary\MotionSummary;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PrepareMotionSummary implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::all()->get();

        //Get users who want a daily summary
        $dailySummaryEmailUsers = $users->filter(function ($user) {
            return $user->preferences['emails']['daily_summary'];
        });

        // Get latest or new motion
        $latestLaunchedMotions = Motion::active()->updatedAfter(Carbon::now()->subHours(24))->get();
        // \DB::enableQueryLog();
        $recentlyClosedMotions = Motion::expired()->closingAfter(Carbon::now()->subHours(24))->get();
        // echo print_r(\DB::getQueryLog());
        $closingSoonMotions = Motion::active()->closingAfter(Carbon::now())->closingBefore(Carbon::now()->addHours(24))->get();


        if ($latestLaunchedMotions->isEmpty() && $recentlyClosedMotions->isEmpty() && $closingSoonMotions->isEmpty()) { //No updates today
            return true;
        }

        foreach ($dailySummaryEmailUsers as $user) {
            $user->notify(new MotionSummary($latestLaunchedMotions, $recentlyClosedMotions, $closingSoonMotions));
        }
    }
}
