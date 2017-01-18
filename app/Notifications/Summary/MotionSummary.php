<?php

namespace App\Notifications\Summary;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MotionSummary extends Notification
{
    use Queueable;

    protected $latestLaunchedMotions;
    protected $recentlyClosedMotions;
    protected $closingSoonMotions;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($latestLaunchedMotions, $recentlyClosedMotions, $closingSoonMotions)
    {
        $this->latestLaunchedMotions = $latestLaunchedMotions;
        $this->recentlyClosedMotions = $recentlyClosedMotions;
        $this->closingSoonMotions = $closingSoonMotions;
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
                    ->subject('Summary of Latest Motions');
                    // ->greeting('greeting')
                    // ->line('heres');

        if (!$this->latestLaunchedMotions->isEmpty()) {
            //  var_dump($this->latestLaunchedMotions);

            foreach ($this->latestLaunchedMotions as $motion) {
                $title = $motion->title;

                var_dump($title);

                $mailMessage
                    ->line($motion->introduction)
                    ->action($title, url('/#/motion/'.$motion->slug));
            }
        }

        if (!$this->recentlyClosedMotions->isEmpty()) {
            $mailMessage->greeting('Recently Closed Motions');
            foreach ($this->recentlyClosedMotions as $motion) {
                $mailMessage
                    ->line($motion->introduction)
                    ->action($motion->title, url('/#/motion/'.$motion->slug));
            }
        }

        if (!$this->closingSoonMotions->isEmpty()) {
            $mailMessage->greeting('BAS')->greeting('Motions Closing Soon');
            foreach ($this->closingSoonMotions as $motion) {
                $mailMessage
                    ->line($motion->introduction)
                    ->action($motion->title, url('/#/motion/'.$motion->slug));
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
