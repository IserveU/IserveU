<?php

namespace App\Console\Commands\Setup;

use Illuminate\Console\Command;

use App\BackgroundImage;
use Setting;
use App\Events\Setup\Defaults;

class SetNewDefaults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:defaults';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets default settings in the app without changing existing settings';

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
     * Put new things and settings in here
     *
     * @return mixed
     */
    public function handle()
    {
        event(new Defaults());       
    }

}
