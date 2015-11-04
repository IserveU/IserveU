<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use App\Motion;
use App\Events\SendDailyEmails;

use Mail;
use Carbon\Carbon;

class EmailDailySummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the emails with daily summaries';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       
        event(new SendDailyEmails());


    }
}
