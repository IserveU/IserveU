<?php

namespace App\Notifications\Authentication;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Setting;

class AccountLocked extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
                    ->subject('Your Account Has Been Locked')
                    ->line('Someone has attempted to login to your account with the wrong password more than '.Setting::get('security.login_attempts_lock')." times so we've locked it to discourage them.")
                    ->line('Your account will be unlocked in '.$this->user->locked_until->diffForHumans());
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
