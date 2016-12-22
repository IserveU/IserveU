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

        //Get users who want a daily summary
        $users = User::preference('motion.notify.user.summary', 1)->get();

        // Get latest or new motion
        $latestLaunchedMotions = Motion::status('published')->publishedAfter(Carbon::now()->subHours(24))->get();

        $recentlyClosedMotions = Motion::status('closed')->closingAfter(Carbon::now()->subHours(24))->closingBefore(Carbon::now())->get();
        // echo print_r(\DB::getQueryLog());
        $closingSoonMotions = Motion::status('published')->closingAfter(Carbon::now())->closingBefore(Carbon::now()->addHours(24))->get();

        if ($latestLaunchedMotions->isEmpty() && $recentlyClosedMotions->isEmpty() && $closingSoonMotions->isEmpty()) { //No updates today
            return true;
        }

        foreach ($users as $user) {
            $user->notify(new MotionSummary($latestLaunchedMotions, $recentlyClosedMotions, $closingSoonMotions));
        }
    }
}
