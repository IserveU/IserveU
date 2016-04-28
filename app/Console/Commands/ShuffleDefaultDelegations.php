<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Delegation;
use App\User;
use App\Department;

class ShuffleDefaultDelegations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:shuffledefaultdelegations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes and recreates default delegations on all users for all departments';

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
        
        Delegation::where('user_set',0)->delete();

        $users = User::with('delegatedFrom')->validVoter()->get();

        $departments = Department::all();
        $representatives = User::representative()->get();

        foreach($users as $user){
            $user->createDefaultDelegations($departments,$representatives);
    
        }
    }
}
