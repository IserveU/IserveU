<?php

namespace App\Console\Commands\Setup;

use App\Jobs\Setup\CreateAdminUser;
use App\Jobs\Setup\SeedDatabaseDefaults;
use App\Jobs\Setup\SetDefaultPermissions;
use App\Jobs\Setup\SetDefaultSettings;
use App\User;
use Artisan;
use Illuminate\Console\Command;
use Setting;

class InitializeApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:initialize {email?} {password?} {wipesettings?} {seed?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Overwrites with default settings and admin account -email -password -wipesettings -seed';

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
     * Put all the things that would only run one time in here.
     *
     * @return mixed
     */
    public function handle()
    {
        \Artisan::call('migrate:refresh');

        \Config::set('mail.driver', 'log'); //The mail singleton will initialize with this and then can't be changed easily once the singleton exists

        // Settings
        if (\File::exists('storage/settings.json')) {
            if (filter_var($this->argument('wipesettings'), FILTER_VALIDATE_BOOLEAN) || $this->confirm('Do you want to wipe your existing /storage/settings.json file?')) {
                Setting::forgetAll();
                Setting::save();
            } else {
                $this->info('Using settings in storage/setting.json');
            }
        }
        dispatch(new SetDefaultSettings());

        // Defaults
        dispatch(new SetDefaultPermissions());

        $email = $this->argument('email');
        if (!$email) {
            $email = $this->anticipate('What is your email?', ['admin@iserve.ca'], 'admin@iserveu.ca');
        }

        $password = $this->argument('password');
        if (!$password) {
            $password = $this->anticipate('What is your password?', [], 'abcd1234');
        }

        $user = User::updateOrCreate([
            'email'         => $email,
            ], [
            'email'              => $email,
            'password'           => $password,
            'first_name'         => 'Default',
            'last_name'          => 'User',
            'status'             => 'public',
            'agreement_accepted' => 1,
        ]);
        $this->info("Creating Admin: $email / $password");

        dispatch(new SeedDatabaseDefaults());

        dispatch(new CreateAdminUser($user));

        if (filter_var($this->argument('seed'), FILTER_VALIDATE_BOOLEAN) || $this->confirm('Do you want to seed the site with dummy data?')) {
            Artisan::call('db:seed', ['--class' => 'FakerDataSeeder']);
        }
    }
}
