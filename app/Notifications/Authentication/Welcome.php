<?php

namespace App\Notifications\Authentication;

use App\OneTimeToken;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Setting;

class Welcome extends Notification
{
    use Queueable;

    public $createdByOther;

    public $text;

    public $footer;

    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $createdByOther = false)
    {
        $this->text = Setting::get('emails.welcome.text');
        $this->footer = Setting::get('emails.footer');
        $this->user = $user;

        if (!$user->password) {
            $this->token = OneTimeToken::generateFor($user);
        }

        $this->createdByOther;
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
            $mailMessage->greeting('An account has been created for you');
        } else {
            $mailMessage->greeting('Welcome,');
        }

        $lines = explode("\n", $this->text);
        foreach ($lines as $line) {
            $mailMessage->line($line);
        }

        if (!$this->user->password) {
            $mailMessage->action('Get Started', url('/#/reset-password/'.$this->token->token));
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
