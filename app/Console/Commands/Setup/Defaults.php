<?php

namespace App\Console\Commands\Setup;

use App\Jobs\Setup\SetDefaultPermissions;
use App\Jobs\Setup\SetDefaultSettings;
use Illuminate\Console\Command;

class Defaults extends Command
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
     * Put new things and settings in here.
     *
     * @return mixed
     */
    public function handle()
    {

        // Will set any new settings since last run
        dispatch(new SetDefaultSettings());

        // Will set any new permissions since last run
        // TODO: Will want to create a seperation beenween assigning the permissions and creating them so that people's permissions don't get wiped each update
        dispatch(new SetDefaultPermissions());
    }
}
