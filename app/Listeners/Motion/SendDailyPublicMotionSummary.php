<?php

namespace App\Listeners\Motion;

use App\Events\SendDailyEmails;
use App\Motion;
use App\User;
use Carbon\Carbon;
use Mail;

class SendDailyPublicMotionSummary
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
     * @param SendDailyEmails $event
     *
     * @return void
     */
    public function handle(SendDailyEmails $event)
    {
        $users = User::validVoter()->get();

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

        $data = [
            'latestLaunchedMotions'     => $latestLaunchedMotions,
            'recentlyClosedMotions'     => $recentlyClosedMotions,
            'closingSoonMotions'        => $closingSoonMotions,
            'title'                     => 'Daily Summary',
        ];

        if ($latestLaunchedMotions->isEmpty() && $recentlyClosedMotions->isEmpty() && $closingSoonMotions->isEmpty()) { //No updates today
            return true;
        }

        foreach ($users as $user) {
            Mail::send('emails.summary', $data, function ($m) use ($user) {
                $m->to($user->email, $user->first_name.' '.$user->last_name)->subject('Daily IserveU Summary');
            });
        }
    }
}
