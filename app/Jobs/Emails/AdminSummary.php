<?php

namespace App\Jobs\Emails;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class AdminSummary implements ShouldQueue
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
        $userAdmins = User::hasRoles(['administrator', 'user-admin'])->get();

        // Get latest or new Motion
        $newUsers = User::where('created_at', '>=', Carbon::now()->subHours(24))->get();

        $data = [
            'newUsers'                  => $newUsers,
        ];

        if ($newUsers->isEmpty() || $userAdmins->isEmpty()) { //No updates today
            return true;
        }

        foreach ($userAdmins as $userAdmin) {
            Mail::send('emails.admin.usersummary', $data, function ($m) use ($userAdmin) {
                $m->to($userAdmin->email, $userAdmin->first_name.' '.$userAdmin->last_name)->subject('Daily IserveU Admin User Summary');
            });
        }
    }
}
