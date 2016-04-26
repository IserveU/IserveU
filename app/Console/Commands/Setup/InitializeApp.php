<?php

namespace App\Console\Commands\Setup;

use Illuminate\Console\Command;

use App\Events\Setup\Initialize;
use App\User;

class InitializeApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:initialize {email?} {password?} {wipesettings?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Overwrites with default settings and admin account -email -password -wipesettings';

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
     * Put all the things that would only run one time in here
     *
     * @return mixed
     */
    public function handle()
    {

        if(\File::exists("storage/settings.json")){
            if(filter_var($this->argument('wipesettings'),FILTER_VALIDATE_BOOLEAN) || $this->confirm('Do you want to wipe your existing /storage/settings.json file?')){
                Setting::forgetAll();
                Setting::save();
            } else {
               $this->info('Using settings in storage/setting.json');
            }
        }

        $email = $this->argument('email');

        if(!$email){
            $email = $this->anticipate('What is your email?', ['admin@iserve.ca'],'admin@iserveu.ca');
        }
 
        $password = $this->argument('password');
        if(!$password){
              $password = $this->anticipate('What is your password?',[],'abcd1234');
        }
        
        $user = User::updateOrCreate([
            'email'         =>  $email
            ],[
            'email'         =>  $email,
            'password'      =>  \Hash::make($password),
            'first_name'    => 'Default',
            'last_name'     => 'User',
            'public'        => 1
        ]);

        $this->info("Creating Admin: $email / $password");

        event(new Initialize($user));   
    }
}
