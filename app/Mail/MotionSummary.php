<?php

namespace App\Mail;

use App\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MotionSummary extends Mailable
{
    use Queueable, SerializesModels;

    public $sections = [];

    public $greeting;

    public $child = 'summary';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $motions)
    {
        $this->sections = $motions;

        $this->greeting = 'A summary of the latest '.Setting::get('jargon.en.motions').' openings, closing and closed on '.config('app.name');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.layout')
                    ->cc('psaunders@sosnewmedia.com')
                    ->subject(Setting::get('jargon.en.motion').' Summary');
    }
}
