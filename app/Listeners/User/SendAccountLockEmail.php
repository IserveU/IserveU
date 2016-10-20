<?php

namespace App\Listeners\User;

use App\Events\User\UserLoginFailed;
use Mail;
use Setting;

class SendAccountLockEmail
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
     * @param UserLoginFailed $event
     *
     * @return void
     */
    public function handle(UserLoginFailed $event)
    {
        $user = $event->user;

        if ($user->login_attempts >= Setting::get('security.login_attempts_lock', 5)) {
            $data = [
                    'user'      => $user,
                    'title'     => 'Account Locked',
            ];

            Mail::send('emails.accountlocked', $data, function ($m) use ($user) {
                $m->to($user->email, $user->first_name.' '.$user->last_name)->subject('Trouble Logging In?');
            });
        }
    }
}
