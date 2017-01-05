<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MotionSummary extends Mailable
{
    use Queueable, SerializesModels;

    public $sections    = ["Latest Launched", "Recently Closed", "Closing Soon"];

    public $greeting    = "Motion Summary: ";

    public $introLines   = ["A summary of motions"];
    
    public $outroLines   = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $motions)
    {
        $this->sections = $motions;

        foreach($motions as $key => $value){
            $this->greeting .= $key;
        }


    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.summary')
                ->subject($this->greeting);
    }
}
