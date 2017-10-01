<?php

namespace App\Notifications\Summary;

use App\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;


class AdminDailyUserSummary extends Notification implements ShouldQueue
{
    use Queueable;

    public $newUsers;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Collection $newUsers, User $user)
    {
       // dd("BULLSHIT");
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
                    ->subject('Daily User Summary')
                    ->greeting('There are new users');

        foreach ($this->newUsers as $newUser) {
            $mailHasContent = true;
            $mailMessage = $mailMessage->line($newUser->first_name.' '.$newUser->last_name.' ('.$newUser->email.')');
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
