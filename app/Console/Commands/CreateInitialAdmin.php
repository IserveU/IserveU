<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use App\Role;
use DB;

class CreateInitialAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:admin {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the admin account. @params {email} {password}';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        parent::__construct();

        $this->user = $user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = $this->user->create(
            ['email'      => $this->argument('email'), 
             'password'   => $this->argument('password'),
             'first_name' => 'Change',
             'last_name'  => 'Name',
             'public'     => 1,
            ]);

        $user->save();

        $userId = DB::table('users')->where('email', '=', $this->argument('email'))->first();
        $user->id = $userId->id;
        $admin = Role::where('name','=','administrator')->first();

        $user->attachRole($admin);

        echo "\n\nADMIN LOGIN WITH: Password: (".$this->argument('password').") Email:".$user->email."\n\n";

    }
}
