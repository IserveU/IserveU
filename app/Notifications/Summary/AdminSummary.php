<?php

namespace App\Notifications\Summary;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class AdminSummary extends Notification
{
    use Queueable;

    protected $newUsers;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Collection $newUsers, User $user)
    {
        $this->newUsers = $newUsers;
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
        $mailMessage = (new MailMessage())
                    ->subject('Admin Summary Email');

        if ($this->user->getPreference('authentication.notify.admin.summary')) {
            $mailMessage = $mailMessage->greeting('There are new users');

            foreach ($this->newUsers as $newUser) {
                $mailHasContent = true;
                $mailMessage = $mailMessage->line($newUser->first_name.' '.$newUser->last_name.' ('.$newUser->email.')');
            }
        }

        return $mailMessage;
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