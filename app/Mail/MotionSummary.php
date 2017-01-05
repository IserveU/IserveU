<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MotionSummary extends Mailable
{
    use Queueable, SerializesModels;

    public $sections = ["Latest Launched", "Recently Closed", "Closing Soon"];


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $motions)
    {
        $this->sections = $motions;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.summary');
    }
}
