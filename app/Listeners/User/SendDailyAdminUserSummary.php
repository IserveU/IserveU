<?php

namespace App\Listeners\User;

use App\Events\User\SendDailyEmails;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use Mail;
use App\User;

class SendDailyAdminUserSummary
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
     * @param  SendDailyEmails  $event
     * @return void
     */
    public function handle(SendDailyEmails $event)
    {
   
        $userAdmins = User::hasRoles(array('administrator','user-admin'))->get();

        // Get latest or new Motion 
        $newUsers      =   User::where('created_at','>=',Carbon::now()->subHours(24))->get();    

        $data = array(
            "newUsers"                  =>      $newUsers
        );

        if($newUsers->isEmpty() || $userAdmins->isEmpty()){ //No updates today
            return true;
        }

        foreach($userAdmins as $userAdmin){
            Mail::send('emails.admin.usersummary',$data, function ($m) use ($userAdmin) {
                $m->to($userAdmin->email, $userAdmin->first_name.' '.$userAdmin->last_name)->subject('Daily IserveU Admin User Summary');
            });
        }

    }
}
