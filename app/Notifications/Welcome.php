<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Setting;

class Welcome extends Notification
{
    use Queueable;

    public $createdByOther = false;

    public $text;

    public $footer;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->text = Setting::get('emails.welcome.text');
        $this->footer = Setting::get('emails.footer');
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
        $mailMessage = (new MailMessage());

        if ($this->createdByOther) {
            $mailMessage = $mailMessage->greeting('An account has been created for you');
        } else {
            $mailMessage = $mailMessage->greeting('Welcome,');
        }

        $lines = explode("\n", $this->text);
        foreach ($lines as $line) {
            $mailMessage = $mailMessage->line($line);
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
