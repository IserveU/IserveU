<?php

namespace App\Mail;

use App\OneTimeToken;
use App\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MotionSummary extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $sections = [];

    public $greeting;

    public $child = 'summary';

    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $motions, OneTimeToken $token = null)
    {
        $this->sections = $motions;
        $this->greeting = 'A summary of the latest '.Setting::get('jargon.en.motions').' openings, closing and closed on '.config('app.name');
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.layout')
                    ->subject(Setting::get('jargon.en.motion').' Summary');
    }
}
