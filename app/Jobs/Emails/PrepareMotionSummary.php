<?php

namespace App\Jobs\Emails;

use App\Mail\MotionSummary;
use App\Motion;
use App\OneTimeToken;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        //Get users who want a daily summary on this day and hour
        $users = static::getTargetUsers();

        $motions = [];

        // Get latest or new motion
        $latestLaunchedMotions = Motion::status('published')->publishedAfter(Carbon::now()->subHours(24))->get();
        if (!$latestLaunchedMotions->isEmpty()) {
            $motions['Latest Launched'] = $latestLaunchedMotions;
        }

        $recentlyClosedMotions = Motion::status('closed')->closingAfter(Carbon::now()->subHours(24))->closingBefore(Carbon::now())->get();
        if (!$recentlyClosedMotions->isEmpty()) {
            $motions['Recently Closed'] = $recentlyClosedMotions;
        }

        $closingSoonMotions = Motion::status('published')->closingAfter(Carbon::now())->closingBefore(Carbon::now()->addHours(24))->get();
        if (!$closingSoonMotions->isEmpty()) {
            $motions['Closing Soon'] = $closingSoonMotions;
        }

        if (!isset($motions) || empty($motions) || $users->isEmpty()) { //No updates today
            return true;
        }

        foreach ($users as $user) {
            Log::info('Sending motion summary to user: '.$user->id);

            $token = null;

            if (!$user->password) {
                $token = OneTimeToken::generateFor($user);
            }

            Mail::to($user)->send(new MotionSummary($motions, $token));
        }
    }

    /**
     * Gets the users who have said they want to get a summary, and get it on this hour of this day.
     *
     * @return Collection users
     */
    public static function getTargetUsers()
    {
        $hour = Carbon::now()->hour;
        $day = strtolower(Carbon::now()->format('l'));

        return User::preference('motion.notify.user.summary.on', 1)->preference("motion.notify.user.summary.times.$day", $hour)->get();
    }
}
